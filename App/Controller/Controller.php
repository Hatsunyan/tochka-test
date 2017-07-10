<?php

/**
 * Абстрактный котроллер
 */
namespace App\Controller;

abstract class Controller
{
    protected $params = [];

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function index()
    {

    }
}