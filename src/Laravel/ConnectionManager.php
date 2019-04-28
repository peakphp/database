<?php

declare(strict_types=1);

namespace Peak\Database\Laravel;

use Illuminate\Database\Connection;

class ConnectionManager
{
    /**
     * @var Connection
     */
    protected static $conn;

    /**
     * @param Connection $conn
     */
    public static function setConnection(Connection $conn)
    {
        self::$conn = $conn;
    }

    /**
     * @return Connection
     */
    public static function getConnection(): Connection
    {
        return self::$conn;
    }
}
