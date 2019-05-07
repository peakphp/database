<?php

declare(strict_types=1);

namespace Peak\Database\Laravel;

use Illuminate\Database\Connection;

class ConnectionManager
{
    /**
     * @var array<string,Connection>
     */
    protected static $conn = [];

    /**
     * @param Connection $conn
     * @param string $env connection environment string, '*' is a wildcard fallback for any undefined env
     */
    public static function setConnection(Connection $conn, string $env = '*')
    {
        self::$conn[$env] = $conn;
    }

    /**
     * @param string $env
     * @return Connection
     * @throws \Exception
     */
    public static function getConnection(string $env): Connection
    {
        if (array_key_exists($env, self::$conn)) {
            return self::$conn[$env];
        } elseif (array_key_exists('*', self::$conn)) {
            return self::$conn['*'];
        }

        throw new \Exception('no connection found for env ['.$env.']');
    }

    public static function flushAll()
    {
        self::$conn = [];
    }
}
