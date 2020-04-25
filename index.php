<?php

require_once 'config/config.php';
require_once 'classes/Autoloader.php';

Autoloader::register();

$dayTime = new DayTime;
$getDayTime = $dayTime->get();

$monthTime = new MonthTime;
$getMonthTime = $monthTime->get();

$message = 'Время за день - ' . $getDayTime . "\n\nВремя за месяц - " . $getMonthTime;

mail('anattoliy90@gmail.com', 'Time', $message);
