<?php


namespace App\Controller;
use App;

class ControllerMain extends Controller
{
    public function index()
    {
        /**
         * я не стал реализовывать систему шаблонов поэтому тут простой html
         */
        require_once __DIR__.'\..\Template\index.html';
    }
}