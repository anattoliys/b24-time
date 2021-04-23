<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\DayTime;
use app\MonthTime;
use app\TelegramBot;
use app\models\User;
use app\core\Log;
use app\models\Time;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1]);

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

    echo '<pre>';print_r($users);echo '</pre>';

    foreach ($users as $recipient) {
        if ($recipient['b24Id'] == 108) {
            // Time::saveTime($recipient);

            // $telegramBot = new TelegramBot;
            // $telegramBot->sendStatisticsByUser($recipient);

            // $telegramBot = new TelegramBot;
            // $telegramBot->sendStatisticsByAllUsers($users, $recipient);
        }
    }
}
