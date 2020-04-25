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
}
