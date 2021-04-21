<?php

use app\DayTime;
use app\MonthTime;
use app\TelegramBot;
use app\models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $key => $user) {
        $dayTime = new DayTime($user);
        $users[$key]['dayTime'] = $dayTime->get();

        $monthTime = new MonthTime($user);
        $users[$key]['monthTime'] = $monthTime->get();

        if ($user['position'] == 'director') {
            unset($users[$key]);
        }
    }

    foreach ($users as $recipient) {
        if ($recipient['position'] == 'director') {
            $telegramBot = new TelegramBot;
            $telegramBot->sendStatisticsByAllUsers($users, $recipient);
        }
    }
}
