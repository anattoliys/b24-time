<?php

use app\DayTime;
use app\MonthTime;
use app\TelegramBot;
use app\models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        if ($user['position'] != 'director') {
            $dayTime = new DayTime($user);
            $user['dayTime'] = $dayTime->get();

            $monthTime = new MonthTime($user);
            $user['monthTime'] = $monthTime->get();

            $telegramBot = new TelegramBot;
            $telegramBot->sendStatisticsByUser($user);
        }
    }
}
