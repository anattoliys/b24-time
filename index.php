<?php

use app\core\Router;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$router = new Router();
$router->render();
