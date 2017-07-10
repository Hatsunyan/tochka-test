<?php

namespace App;

use App\DataStorage\StorageFactory;

abstract class Config
{
    // типа хранилища
    protected static $storageType = StorageFactory::JSON;

    protected static $routes =
        [
            ['/',['controller'=>'Main']],
            ['/api/v1/task',['controller'=>'APIv1','action'=>'task']],
            ['/api/v1/task/{id:int}',['controller'=>'APIv1','action'=>'taskItem']],
            ['/cron/update',['controller'=>'Cron','action'=>'update']],
            ['/cron/clear',['controller'=>'Cron','action'=>'clear']],
            ['/cron/init',['controller'=>'Cron','action'=>'init']],
        ];

    protected static $mysqlDatabase = 'tochka';
    protected static $mysqlUser = 'root';
    protected static $mysqlPassword = '';

    //limit on page
    protected static $limit = 10;

    /**
     * @return array
     */
    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getStorageType() : string
    {
        return self::$storageType;
    }

    /**
     * @return string
     */
    public static function getMysqlDatabase(): string
    {
        return self::$mysqlDatabase;
    }

    /**
     * @return string
     */
    public static function getMysqlUser(): string
    {
        return self::$mysqlUser;
    }

    /**
     * @return string
     */
    public static function getMysqlPassword(): string
    {
        return self::$mysqlPassword;
    }

    public static function getLimit(): int
    {
        return self::$limit;
    }
}