# Peak/Database

The purposes of this package are:
 
 - provide generic agnostic database tools for DDD and Clean architecture
 - provide database migration with [Phinx migration](https://packagist.org/packages/robmorgan/phinx)
 - facilitate the integration of [Laravel Database](https://packagist.org/packages/illuminate/database) in non-laravel project
 

## Installation

     composer require peak/database
  
## Laravel Database Usage

```php
use Peak\Database\Laravel\DatabaseService;

$config = [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'database',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
];
    
$db = (new DatabaseService())->createConnection($config, 'connectionName');
```

That's it! Check out [Laravel Query Builder](https://laravel.com/docs/5.8/queries) for more info on how to make queries.
     
## Database Migration Usage

For database migrations, create a file at the root of your project name ```phinx.php```. This file should return an array of Phinx configuration.

```php
<?php

namespace {

    use Peak\Database\Common\LaravelPhinxMigration;
    use Peak\Database\Laravel\LaravelDatabaseService;
    use Peak\Database\Laravel\LaravelConnectionManager;
    use Peak\Database\Phinx\PhinxConfigService;
    use Peak\Database\Phinx\PhinxEnvConfig;

    require __DIR__.'/vendor/autoload.php';

    try {
        $env = getenv();
        $db = (new LaravelDatabaseService())->createConnection([
           'driver' => $env['DB_DRIVER'],
           'host' => $env['DB_HOST'],
           'port' => $env['DB_PORT'],
           'database' => $env['DB_DATABASE'],
           'username' => $env['DB_USERNAME'],
           'password' => $env['DB_PASSWORD'],
           'charset' => $env['DB_CHARSET'],
           'collation' => $env['DB_COLLATION'],
           'prefix' => $env['DB_PREFIX'],
       ], 'connectionName');
        LaravelConnectionManager::setConnection($db, 'prod');

        return (new PhinxConfigService())
            ->create(
                'migrations',
                LaravelPhinxMigration::class,
                'migrations',
                'prod',
                [
                    new PhinxEnvConfig('prod', [
                        'name' => $db->getDatabaseName(),
                        'connection' => $db->getPdo(),
                    ])
                ]
            );

    } catch(\Exception $e) {
        die($e->getMessage());
    }
}
```

This ``phinx.php`` above will allow the usage of Laravel Database directly in your migrations with the help of ``LaravelPhinxMigration``:

```php
<?php

use Peak\Database\Laravel\LaravelPhinxMigration;
use Illuminate\Database\Schema\Blueprint;

class Users extends LaravelPhinxMigration
{
    public function up()
    {
        $this->db->getSchemaBuilder()->create('users', function(Blueprint $table){
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $this->tsColumns($table);
            $table->timestamp('lastSeen')->nullable()->default(null);
        });
    }

    public function down()
    {
        $this->db->getSchemaBuilder()->drop('users');
    }
}

```

## Generic tools

The purpose of generic tools is to help you express a query without being attached to a particular database framework or any database at all.

 - Use ``QueryFilters`` to express "where" statements.
 - Use ``QueryPagination`` to express pagination like order by and limit/offset statement.
 
It is important to note that those generic tools doesn't to do anything by themselves. They serve mainly as "boundary" interfaces between a domain or use case and your actual real database or storage implementation. In your final implementation, you will need builder/helper to translate generic expression to your actual database framework/orm.


Example of creating generic query "where" filters and generic query "pagination"

```php

$queryFilters = new QueryFilters();
$queryFilters
    ->setColumns(['id', 'title'])
    ->where('level', '6', '>')
    ->orWhere('level', '2', '<')
    ->orWhereArray((new QueryFilters())
        ->where('status', 'online')
        ->where('type', '2')
        ->whereNull('ban')
        ->whereNotNull('deletedAt')
    );

$queryPagination = new QueryPagination(
    $column, 
    $direction, 
    $pageNumber, 
    $itemsPerPages
);

```

Pass the ``$queryFilters`` and ``$queryPagination`` to a use case. This will help to create a boundary between use cases and repositories because the use case doesn't have to know the details of your implementation (database framework/orm, etc)

```php
<?php

namespace Domain\UseCase;

use Peak\Database\Generic\QueryFiltersInterface;
use Peak\Database\Generic\QueryPaginationInterface;

class MyUseCase 
{
    // ...
    public function execute(
        QueryFiltersInterface $queryFilters,
        QueryPaginationInterface $queryPagination
    ) {
        // do things
        // ...
       
        return $this->repository->getMany($queryFilters, $queryPagination);
    }
}
```

And finally, we use ``LaravelGenericHelper`` in our repository implementation to transform generic ``QueryFiltersInterface`` to actual laravel query builder "where" expressions;

```php
<?php

use Domain\Repository\MyRepositoryInterface;
use Peak\Database\Generic\QueryFiltersInterface;
use Peak\Database\Generic\QueryPaginationInterface;
use Peak\Database\Common\LaravelGenericHelper;

class MyRepository implements MyRepositoryInterface
{
    // ...
    
   public function getMany(
       QueryFiltersInterface $queryFilters,
       QueryPaginationInterface $queryPagination
   ) {
       $qb = $this->table('tusers');
       $qb = LaravelGenericHelper::filterQuery($qb, $queryFilters);
       $qb = LaravelGenericHelper::paginateQuery($qb, $queryPagination);
       return $qb->get();
   }
}
```

We could simply use laravel query builder directly in our use case but this could also tie the code to much to specific database library (here laravel database). By using generic query filters and pagination, it becomes really easy to tests repository and use cases without a real database connection.

#### Important security information on pagination and filters with Laravel Database
 
From Laravel Database docs:

The Laravel query builder uses PDO parameter binding to protect your application against SQL injection attacks. There is no need to clean strings being passed as bindings. But:

"PDO does not support binding column names. Therefore, you should never allow user input to dictate the column names referenced by your queries, including "order by" columns, etc. If you must allow the user to select certain columns to query against, always validate the column names against a white-list of allowed columns."

If you let your user choose the column names, you should create a class that extends ``AbstractRestrictedQueryPagination`` to protected from unwanted column names.

```php

class UserPagination extends AbstractRestrictedQueryPagination
{
    protected $allowedColumns = [
        'username', 'email', 'createdAt', 'updatedAt', 'deletedAt'
    ];
    
    protected $allowedDirections = [
        'asc', 'desc'
    ];
}


// and use it like this:

$queryPagination = new UserPagination(
    $column, 
    $direction, 
    $pageNumber, 
    $itemsPerPages
);
```

The same can be applied to query filters columns and operators with ``AbstractRestrictedQueryFilters``:

```php

class UserFilters extends AbstractRestrictedQueryFilters
{
    protected $allowedColumns = [
        'username', 'email', 'createdAt', 'updatedAt', 'deletedAt'
    ];
    
    protected $allowedOperators = [
        '=', '>', '<', 'like'
    ];
}


// and use it like this:

$queryFilters = new UserFilters();
$queryFilters
    ->where('username', 'bob', '=')
    //...
```