<?php
/**
 * Котроллер управления данными, должен работать по крону, но для простоты эксперемента
 * работает по ссылке
 */

namespace App\Controller;


use App\DataGenerator;
use App\DataStorage\StorageFactory;


class ControllerCron extends Controller
{
    public function update()
    {
        $data = (new DataGenerator())->make();
        $storage = StorageFactory::makeStorage(true);
        $storage->setData($data);
        $storage->saveData();
    }

    public function clear()
    {
        $storage = StorageFactory::makeStorage(true);
        $storage->clearInactiveState();
    }

    public function init()
    {
        $storage = StorageFactory::makeStorage();
        $storage->setData((new DataGenerator())->make());
        $storage->initStorage();
    }
}