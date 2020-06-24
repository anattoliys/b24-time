<?php

class Time
{
    public static function saveTime()
    {
        $date = date('Y-m-d');
        $dayTime = new DayTime;
        $getDayTime = $dayTime->get();
        $monthTime = new MonthTime;
        $getMonthTime = $monthTime->get();

        $db = Db::connect();
        $sql = 'INSERT INTO time (dayTime, monthTime, date) VALUES (?, ?, ?)';

        $query = $db->prepare($sql);
        $time = $query->execute([$getDayTime, $getMonthTime, $date]);

        $query = null;
        $db = null;

        return $time;
    }

    public static function getTime()
    {
        $time = [];
        $date = date('Y-m-01');

        $db = Db::connect();
        $sql = "SELECT * FROM time WHERE date >= '$date'";
        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $time[] = $row;
        }

        return $time;
    }
}
