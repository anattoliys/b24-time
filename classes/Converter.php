<?php

class Converter
{
    /**
     * Converts minutes by format
     *
     * @param integer $time
     * @param string $format
     * @return string
     */
    public static function convertMinutesByFormat($time, $format = '%02d:%02d')
    {
        if ($time < 1) {
            return;
        }

        $hours = floor($time / 60);
        $minutes = ($time % 60);

        return sprintf($format, $hours, $minutes);
    }

    /**
     * Converts time to seconds
     *
     * @param string $time
     * @return integer
     */
    public static function convertToSeconds($time)
    {
        sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
        $timeSeconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

        return $timeSeconds;
    }
}
