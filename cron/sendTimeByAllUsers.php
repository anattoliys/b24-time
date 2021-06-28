<?php

use app\TelegramBot;
use app\models\User;

$_SERVER['DOCUMENT_ROOT'] = '/var/www/tolik/data/www/b24-time.bx100.ru';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1], true);
$directors = $userObj->getList(['active' => 1, 'position' => 'director']);

if (!empty($users) && !empty($directors)) {
    foreach ($directors as $director) {
        $telegramBot = new TelegramBot;
        $telegramBot->sendStatisticsByAllUsers($users, $director);
    }
}
