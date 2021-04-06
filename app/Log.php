<?php

class Log
{
    /**
     * Writes a log to a file
     *
     * @param string $message
     * @return void
     */
    public static function getMessage($message)
    {
        echo '<pre>';print_r(gettype($message));echo '</pre>';

        $fileName = $_SERVER['DOCUMENT_ROOT'] . '/log.txt';
        $now = date('d.m.Y H:i:s');
        $sep = "\r\n----------------------------------\r\n";

        $message = print_r($message, 1);

        file_put_contents($fileName, $now . $sep . $message . $sep, FILE_APPEND);
    }
}
