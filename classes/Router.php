<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routsFolder = $_SERVER['DOCUMENT_ROOT'] . '/config/routes.php';
        $this->routes = require $routsFolder;
    }

    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function render()
    {
        $uri = $this->getURI();

        $controllerName = ucfirst($uri) . 'Controller';
        $controllerFile = $_SERVER['DOCUMENT_ROOT'] . '/controllers/' . $controllerName . '.php';
        $actionName = 'actionIndex';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        }

        $controllerObject = new $controllerName;

        call_user_func_array([$controllerObject, $actionName], []);
    }
}
