<?php

use app\models\User;
use app\models\Time;

$_SERVER['DOCUMENT_ROOT'] = '/var/www/tolik/data/www/b24-time.bx100.ru';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1], true);

if (!empty($users)) {
    foreach ($users as $user) {
        Time::save($user);
    }
}
