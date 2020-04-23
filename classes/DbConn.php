<?php

class DbConn
{
    public static function connect()
    {
        $paramsPath = ROOT_DIR . '/config/dbParams.php';
        $params = include($paramsPath);

        try {
            $conn = new PDO("mysql:host={$params['serverName']};dbname={$params['dbnName']}", $params['userName'], $params['password']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
