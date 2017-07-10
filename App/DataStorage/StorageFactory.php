<?php
/**
 * фабрика для создания хранилища
 */

namespace App\DataStorage;


use App\Config;

abstract class StorageFactory
{
    public const JSON = 1;
    public const Mysql = 2;

    static public function makeStorage(bool $preload = false) : IStorage
    {
        $state = self::selectStorageState($preload);
        switch (Config::getStorageType())
        {
            case self::JSON:
                return new JsonStorage($state);
                break;
            case self::Mysql:
                return new MysqlStorage($state);
                break;
            default :
                throw new \Exception('Storage not find');
        }
    }

    /**
     * Выбирает текущее состояние
     * @param $preload
     * @return int
     */
    protected static function selectStorageState($preload)
    {
        $date = new \DateTime();
        if($preload)
        {
            $date->modify('+ 1 hour');
        }
        $hour  = $date->format('H');
        return ($hour % 2 === 0) ? 1 : 2;
    }

    /**
     * Время когда запись становится не актуальной
     * @return int
     */
    public static function storageExpired()
    {
        $date = new \DateTime();
        $date->modify('+ 1 hour');
        return \DateTime::createFromFormat('Y-d-m H',$date->format('Y-d-m H'))->getTimestamp();
    }
}