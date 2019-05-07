<?php

use Peak\Database\Laravel\ConnectionManager;
use Illuminate\Database\Connection;

class ConnectionManagerTest extends \PHPUnit\Framework\TestCase
{


    /**
     * @throws ReflectionException
     */
    public function testWildcard()
    {
        ConnectionManager::flushAll();
        ConnectionManager::setConnection(
            $this->createMock(Connection::class)
        );

        $this->assertTrue(ConnectionManager::getConnection('dev') instanceof Connection);
        $this->assertTrue(ConnectionManager::getConnection('prod') instanceof Connection);
    }

    /**
     * @throws ReflectionException
     */
    public function testException1()
    {
        ConnectionManager::flushAll();
        $this->expectException(\Exception::class);
        ConnectionManager::setConnection(
            $this->createMock(Connection::class),
            'dev'
        );

        ConnectionManager::getConnection('prod');
    }

    /**
     * @throws ReflectionException
     */
    public function testException2()
    {
        ConnectionManager::flushAll();
        $this->expectException(\Exception::class);
        ConnectionManager::getConnection('prod');
    }

}