# Peak/Database

The purpose of this package is to facilitate the integration of [Laravel Database](https://packagist.org/packages/illuminate/database) and [Phinx migration](https://packagist.org/packages/robmorgan/phinx) in any standalone application or framework.

## Installation

     composer require peak/database
  
## Database Usage

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
     
## Migration Usage

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

This ``phinx.php`` above will allow the usage of Laravel Database directly in your migrations:

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
            $table->string('username');
            $table->string('email');
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