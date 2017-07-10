<?php
/**
 * Created by PhpStorm.
 * User: Hatsu
 * Date: 11.07.2017
 * Time: 18:07
 */
use App\Lib\Router;
use App\Config;
try
{
    $router = new Router();
    $router->addFromArray(Config::getRoutes());
    $params = $router->find_route();
    $controllerName = 'Controller'.$params['controller'];
    if(!file_exists('App/Controller/'.$controllerName.'.php'))
    {
        throw new Exception('Cant find Controller '.$params['controller']);
    }
    $controllerName = 'App\Controller\\'.$controllerName;
    $controller = new $controllerName($params);
    $action = $params['action'];
    if(!method_exists($controller, $action))
    {

        throw new Exception('Cant find method '.$params['action']);
    }
    $controller->$action();
}catch (\Exception $e)
{
   //При любых ошибках ловим ошибку и пишем в лог или шлем на почти
    $errorController = new App\Controller\ControllerError();
    $errorController->index($e->getMessage());
}
