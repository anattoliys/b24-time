<?php

Class ConvertMinutes
{
    public static function exec($time, $format = "%02d:%02d")
    {
        if ($time < 1) {
            return;
        }
    
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }
}
