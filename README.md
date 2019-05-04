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

    use Peak\Database\Laravel\DatabaseService;
    use Peak\Database\Laravel\ConnectionManager;
    use Peak\Database\Laravel\PhinxMigration;
    use Peak\Database\Phinx\ConfigService;
    use Peak\Database\Phinx\EnvConfig;

    require __DIR__.'/vendor/autoload.php';

    $env = getenv();
    $config = [
        'driver' => $env['DB_DRIVER'],
        'host' => $env['DB_HOST'],
        'port' => $env['DB_PORT'],
        'database' => $env['DB_DATABASE'],
        'username' => $env['DB_USERNAME'],
        'password' => $env['DB_PASSWORD'],
        'charset' => $env['DB_CHARSET'],
        'collation' => $env['DB_COLLATION'],
        'prefix' => $env['DB_PREFIX'],
    ];

    try {
        $db = (new DatabaseService())->createConnection($config, 'connectionName');
        ConnectionManager::setConnection($db);

        return (new ConfigService())
            ->create(
                'migrations',
                PhinxMigration::class,
                'migrations',
                'prod',
                [
                    new EnvConfig('prod', [
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

use Peak\Database\Laravel\PhinxMigration;
use Illuminate\Database\Schema\Blueprint;

class Users extends PhinxMigration
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