<?php

declare(strict_types=1);

namespace Peak\Database\Laravel;

use Illuminate\Container\Container as Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use Illuminate\Events\Dispatcher;

class LaravelDatabaseService
{
    /**
     * @param array $config
     * @param string $connectionName
     * @return Connection
     */
    public function createConnection(array $config, string $connectionName): Connection
    {
        $capsule = new Capsule();
        $capsule->addConnection($config, $connectionName);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule->getConnection($connectionName);
    }
}