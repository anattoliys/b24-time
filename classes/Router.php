<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routsFolder = $_SERVER['DOCUMENT_ROOT'] . '/config/routes.php';
        $this->routes = require $routsFolder;
    }

    /**
     * Getting curret url
     *
     * @return string
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $params = strstr($_SERVER['REQUEST_URI'], '?');
            $pureUrl = str_replace($params, '', $_SERVER['REQUEST_URI']);
            $uri = trim($_SERVER['REQUEST_URI'], '/');

            if ($pureUrl == '/') {
                $uri = trim('index/' . $params, '/');
            }

            return $uri;
        }
    }

    /**
     * Rendering the desired page
     *
     * @return void
     */
    public function render()
    {
        $uri = $this->getURI();
        $result = 0;

        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                $segments = explode('/', $internalRoute);

                $controllerName = ucfirst(array_shift($segments)) . 'Controller';
                $actionName = 'action' . ucfirst(array_shift($segments));
                $parameters = $segments;

                $controllerFile = $_SERVER['DOCUMENT_ROOT'] . '/controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                }

                $controllerObject = new $controllerName;

                $result = call_user_func_array([$controllerObject, $actionName], $parameters);

                if ($result != null) {
                    break;
                }
            }
        }

        if (!$result) {
            require_once $_SERVER['DOCUMENT_ROOT'] . '/404.php';
        }
    }
}
