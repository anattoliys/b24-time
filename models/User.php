<?php

class User
{
    private $rate = 400;

    public function create($chatId, $name, $messagedId)
    {
        $date = date('Y-m-d G:i:s');

        $db = Db::connect();
        $sql = 'INSERT INTO users (name, chatId, rate, startMessageId, creationDate) VALUES (?, ?, ?, ?, ?)';

        $query = $db->prepare($sql);
        $user = $query->execute([$name, $chatId, $this->rate, $messagedId, $date]);

        $query = null;
        $db = null;

        return $user;
    }

    public static function getStartMessageId($chatId)
    {
        $startMessageId = 0;
        $users = [];

        $db = Db::connect();
        $sql = "SELECT id, startMessageId FROM users WHERE chatId = '$chatId'";

        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $users[] = $row;
        }

        $startMessageId = end($users)['startMessageId'];

        $query = null;
        $db = null;

        return $startMessageId;
    }

    public static function getByChatId($chatId)
    {
        $id = [];

        $db = Db::connect();
        $sql = "SELECT id FROM users WHERE chatId = '$chatId'";

        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $id[] = $row;
        }

        $query = null;
        $db = null;

        return $id[0];
    }

    public function setB24Id($chatId, $messaged)
    {
        $db = Db::connect();
        $sql = 'UPDATE users SET b24Id = ? WHERE chatId = ?';

        $query = $db->prepare($sql);
        $result = $query->execute([$messaged, $chatId]);

        $query = null;
        $db = null;

        return $result;
    }

    public function getAll()
    {
        $users = [];

        $db = Db::connect();
        $sql = 'SELECT * FROM users';

        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $users[] = $row;
        }

        $query = null;
        $db = null;

        return $users;
    }
}
