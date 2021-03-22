<?php

class Time
{
    /**
     * Writing the time to db
     *
     * @return bool
     */
    public static function saveTime($data)
    {
        $date = date('Y-m-d');

        $db = Db::connect();
        $sql = 'INSERT INTO time (userId, dayTime, monthTime, date) VALUES (?, ?, ?, ?)';

        $query = $db->prepare($sql);
        $time = $query->execute([$data['userId'], $data['dayTime'], $data['monthTime'], $date]);

        $query = null;
        $db = null;

        return $time;
    }

    /**
     * Getting time of current month from db
     *
     * @return array
     */
    public static function getUserMonthTime($userId)
    {
        $time = [];
        $date = date('Y-m-01');

        $db = Db::connect();
        $sql = "SELECT * FROM time WHERE date >= '$date' AND userId = '$userId'";
        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $time[] = $row;
        }

        $query = null;
        $db = null;

        return $time;
    }
}
