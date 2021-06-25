<?php

use app\TelegramBot;
use app\models\User;

$_SERVER['DOCUMENT_ROOT'] = '/var/www/tolik/data/www/b24-time.bx100.ru';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1, '!position' => 'director'], true);

if (!empty($users)) {
    foreach ($users as $user) {
        $telegramBot = new TelegramBot;
        $telegramBot->sendStatistic($user);
    }
}
