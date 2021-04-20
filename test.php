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
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        if ($user['b24Id'] == 108) {
            $dayTime = new DayTime($user);
            $user['dayTime'] = $dayTime->get();

            $monthTime = new MonthTime($user);
            $user['monthTime'] = $monthTime->get();

            echo '<pre>';print_r($user);echo '</pre>';

            // Time::saveTime($user);
            // $telegramBot = new TelegramBot;
            // $telegramBot->dispatcher($user, true);
        }
    }
}
