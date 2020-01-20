<?php

require_once("curl.php");
require_once("functions.php");

$all_minutes = 0;
$full_time = 0;

if($result["total"] > 0) {
    foreach($result["result"] as $minutes) {
        $all_minutes += intval($minutes["MINUTES"]);
    }
}

if($all_minutes > 0) {
    $full_time = convertMinutes($all_minutes);
}

mail("anattoliy90@gmail.com", "Day time", "Время за день - " . $full_time);
