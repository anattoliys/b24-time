<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'classes/Autoloader.php';

define('ROOT_DIR', __DIR__);

Autoloader::register();

$dayTime = new DayTime;
$getDayTime = $dayTime->get();

$monthTime = new MonthTime;
$getMonthTime = $monthTime->get();

$message = 'Время за день - ' . $getDayTime . '\n\nВремя за месяц - ' . $getMonthTime;

mail('anattoliy90@gmail.com', 'Time', $message);
