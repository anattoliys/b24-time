<?php

class Db
{
    /**
     * Establishes a connection to the database
     *
     * @return void
     */
    public static function connect()
    {
        try {
            $conn = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }
}
