<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/Autoloader.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        $dayTime = new DayTime($user['b24Id']);
        $getDayTime = $dayTime->get();

        $monthTime = new MonthTime($user['b24Id']);
        $getMonthTime = $monthTime->get();

        $data = [
            'name' => $user['name'],
            'chatId' => $user['chatId'],
            'rate' => $user['rate'],
            'dayTime' => $getDayTime,
            'monthTime' => $getMonthTime,
        ];

        $telegramBot = new TelegramBot;
        $telegramBot->dispatcher($data, true);
    }
}
