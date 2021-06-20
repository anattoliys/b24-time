<?php

use app\DayTime;
use app\MonthTime;
use app\TelegramBot;
use app\models\User;

$_SERVER['DOCUMENT_ROOT'] = '/var/www/tolik/data/www/b24-time.bx100.ru';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1]);
$directors = [];

if (!empty($users)) {
    foreach ($users as $key => $user) {
        $dayTime = new DayTime($user);
        $users[$key]['dayTime'] = $dayTime->get();

        $monthTime = new MonthTime($user);
        $users[$key]['monthTime'] = $monthTime->get();

        if ($user['position'] == 'director') {
            $directors[] = $user;

            unset($users[$key]);
        }
    }

    if (!empty($directors)) {
        foreach ($directors as $director) {
            $telegramBot = new TelegramBot;
            $telegramBot->sendStatisticsByAllUsers($users, $director);
        }
    }
}
