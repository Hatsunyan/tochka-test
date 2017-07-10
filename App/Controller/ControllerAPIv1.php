<?php
/**
 * Котроллер API отдает все запросы по API
 */

namespace App\Controller;


use App\DataStorage\StorageFactory;

class ControllerAPIv1 extends Controller
{
    protected $storage;

    public function task()
    {
        $storage = StorageFactory::makeStorage();
        $data = $storage->getData();
        $clearData = [];

        //срезаем лишнее
        foreach ($data as $d)
        {
            $clearData[$d['id']] =
                [
                    'id' => $d['id'],
                    'title' => $d['title'],
                    'date'  => $d['date']
                ];
        }

        echo json_encode($clearData,true);
    }

    public function taskItem()
    {
        $storage = StorageFactory::makeStorage();
        $item = $storage->getItemById($this->params['id']);
        $item['expired'] = StorageFactory::storageExpired();
        echo json_encode($item,true);
    }
}