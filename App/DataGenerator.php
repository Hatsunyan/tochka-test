<?php

/**
 * генератор данных
 */

namespace App;


class DataGenerator
{
    public function make() : array
    {
        $data = [];
        for($i = 1; $i <= 1000; $i++)
        {
            $data[$i] =
                [
                    'id'     => $i,
                    'title'  => 'Задача '.$i,
                    'date'   => (new \DateTime())->modify('+'.$i.'hour')->format('Y-m-d H-i-s'),
                    'author' => 'Автор '.$i,
                    'status' => 'Статус '.$i,
                    'description' => 'Описание '.$i,
                ];
        }
        return $data;
    }
}