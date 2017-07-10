<?php

//require_once 'vendor/autoload.php';

spl_autoload_register(function ($class) //autoLoad
{
    $file =  __DIR__ .'\\'.$class.'.php';
    if(file_exists($file))
    {
        require_once $file;
    }
});
require_once 'App/start.php';

