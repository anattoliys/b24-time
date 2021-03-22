<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Autoloader.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        $dayTime = new DayTime($user['b24Id']);
        $getDayTime = $dayTime->get();
        
        $monthTime = new MonthTime($user['b24Id']);
        $getMonthTime = $monthTime->get(true);

        $data = [
            'userId' => $user['id'],
            'dayTime' => $getDayTime,
            'monthTime' => $getMonthTime,
        ];

        Time::saveTime($data);
    }
}
