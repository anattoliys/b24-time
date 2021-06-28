<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\TelegramBot;
use app\models\User;
use app\core\Log;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
// $users = $userObj->getList(['active' => 1], true);
$directors = $userObj->getList(['b24Id' => 108]);

echo '<pre>';print_r($directors);echo '</pre>';

// foreach ($directors as $director) {
//     $telegramBot = new TelegramBot;
//     $telegramBot->sendStatistic($director);
// }

// if (!empty($users) && !empty($directors)) {
//     foreach ($directors as $director) {
//         $telegramBot = new TelegramBot;
//         $telegramBot->sendStatisticsByAllUsers($users, $director);
//     }
// }
