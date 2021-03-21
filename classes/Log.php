<?php

class Log
{
    public static function getMessage($message)
    {
        $fileName = $_SERVER['DOCUMENT_ROOT'] . '/log.txt';
        $now = date('d.m.Y H:i:s');
        $sep = "\r\n----------------------------------\r\n";

        $message = print_r($message, 1);
        
        file_put_contents($fileName, $now . $sep . $message . $sep, FILE_APPEND);
    }
}
