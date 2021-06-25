<?php

namespace app\models;

use \PDO;
use app\core\Db;

class Time
{
    /**
     * Writing the time to db
     *
     * @return bool
     */
    public static function save($data)
    {
        $date = date('Y-m-d');

        $db = Db::connect();
        $sql = 'INSERT INTO time (b24Id, dayTime, monthTime, date) VALUES (?, ?, ?, ?)';

        $query = $db->prepare($sql);
        $time = $query->execute([$data['b24Id'], $data['dayTimeSeconds'], $data['monthTimeSeconds'], $date]);

        $query = null;
        $db = null;

        return $time;
    }

    /**
     * Getting time of current month from db
     *
     * @return array
     */
    public static function getMonthTime($b24Id)
    {
        $time = [];
        $date = date('Y-m-01');

        $db = Db::connect();
        $sql = "SELECT * FROM time WHERE date >= '$date' AND b24Id = '$b24Id'";
        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $time[] = $row;
        }

        $query = null;
        $db = null;

        return $time;
    }
}
