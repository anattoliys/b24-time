<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\DayTime;
use app\MonthTime;
use app\models\User;
use app\models\Time;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        $dayTime = new DayTime($user);
        $getDayTime = $dayTime->get();

        $monthTime = new MonthTime($user);
        $getMonthTime = $monthTime->get(true);

        $data = [
            'userId' => $user['id'],
            'name' => $user['name'],
            'chatId' => $user['chatId'],
            'b24Id' => $user['b24Id'],
            'rate' => $user['rate'],
            'position' => $user['position'],
            'dayTime' => $getDayTime,
            'monthTime' => $getMonthTime,
        ];

        Time::saveTime($data);
    }
}
