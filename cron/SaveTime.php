<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/Autoloader.php';

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
