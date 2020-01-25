<?php

require_once "classes/Autoloader.php";

define("ROOT_DIR", __DIR__);

Autoloader::register();

$day_time = DayTime::get();

mail("anattoliy90@gmail.com", "Time", "Время за день - " . $day_time);
