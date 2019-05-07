<?php

use Peak\Database\Laravel\LaravelConnectionManager;
use Illuminate\Database\Connection;

class ConnectionManagerTest extends \PHPUnit\Framework\TestCase
{


    /**
     * @throws ReflectionException
     */
    public function testWildcard()
    {
        LaravelConnectionManager::flushAll();
        LaravelConnectionManager::setConnection(
            $this->createMock(Connection::class)
        );

        $this->assertTrue(LaravelConnectionManager::getConnection('dev') instanceof Connection);
        $this->assertTrue(LaravelConnectionManager::getConnection('prod') instanceof Connection);
    }

    /**
     * @throws ReflectionException
     */
    public function testException1()
    {
        LaravelConnectionManager::flushAll();
        $this->expectException(\Exception::class);
        LaravelConnectionManager::setConnection(
            $this->createMock(Connection::class),
            'dev'
        );

        LaravelConnectionManager::getConnection('prod');
    }

    /**
     * @throws ReflectionException
     */
    public function testException2()
    {
        LaravelConnectionManager::flushAll();
        $this->expectException(\Exception::class);
        LaravelConnectionManager::getConnection('prod');
    }

}