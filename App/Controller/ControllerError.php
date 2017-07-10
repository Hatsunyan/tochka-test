<?php
/**
 * Контроллер ошибок
 */

namespace App\Controller;


class ControllerError
{
    public function index()
    {
        header("HTTP/1.0 404 Not Found");
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        echo 'ошибка';
        die();
    }
}