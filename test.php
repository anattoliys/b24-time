<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\DayTime;
use app\MonthTime;
use app\TelegramBot;
use app\models\User;
use app\core\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getAll();

if (!empty($users)) {
    foreach ($users as $user) {
        if ($user['b24Id'] == 108) {
            $dayTime = new DayTime($user);
            $getDayTime = $dayTime->get();

            $monthTime = new MonthTime($user);
            $getMonthTime = $monthTime->get();

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

            echo '<pre>';print_r($data);echo '</pre>';

            // $telegramBot = new TelegramBot;
            // $telegramBot->dispatcher($data, true);
        }
    }
}
