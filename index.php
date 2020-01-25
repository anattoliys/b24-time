<?php

require_once "classes/Autoloader.php";

define("ROOT_DIR", __DIR__);

Autoloader::register();

$day_time = DayTime::get();
$month_time = MonthTime::get();

$message = "Время за день - " . $day_time . "\nВремя за месяц - " . $month_time;

mail("anattoliy90@gmail.com", "Time", $message);
