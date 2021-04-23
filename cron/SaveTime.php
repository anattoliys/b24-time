<?php

use app\DayTime;
use app\MonthTime;
use app\models\User;
use app\models\Time;

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/core/prolog.php';

$userObj = new User;
$users = $userObj->getList(['active' => 1]);

if (!empty($users)) {
    foreach ($users as $user) {
        if ($user['position'] != 'director') {
            $dayTime = new DayTime($user);
            $user['dayTime'] = $dayTime->get();

            $monthTime = new MonthTime($user);
            $user['monthTime'] = $monthTime->get();

            Time::saveTime($user);
        }
    }
}
