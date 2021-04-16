<?php

namespace app\models;

use \PDO;
use app\core\Db;

class User
{
    /**
     * Сreates a new user
     *
     * @param integer $chatId
     * @param string $name
     * @param integer $messageId
     * @return array
     */
    public function create($chatId, $name, $messageId)
    {
        $date = date('Y-m-d G:i:s');

        $db = Db::connect();
        $db->query("SET character_set_client='utf8mb4'");
        $db->query("SET collation_connection='utf8mb4_general_ci'");
        $sql = 'INSERT INTO users (name, chatId, startMessageId, creationDate) VALUES (?, ?, ?, ?)';

        $query = $db->prepare($sql);
        $user = $query->execute([$name, $chatId, $messageId, $date]);

        $query = null;
        $db = null;

        return $user;
    }

    /**
     * Gets the start message id
     *
     * @param integer $chatId
     * @return integer
     */
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

        $startMessageId = isset(end($users)['startMessageId']) ? end($users)['startMessageId'] : 0;

        $query = null;
        $db = null;

        return $startMessageId;
    }

    /**
     * Gets user id by b24 id
     *
     * @param integer $chatId
     * @return array
     */
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

    /**
     * Sets b24 id for user
     *
     * @param integer $chatId
     * @param string $message
     * @return bool
     */
    public function setB24Id($chatId, $message)
    {
        $db = Db::connect();
        $sql = 'UPDATE users SET b24Id = ? WHERE chatId = ?';

        $query = $db->prepare($sql);
        $result = $query->execute([$message, $chatId]);

        $query = null;
        $db = null;

        return $result;
    }

    /**
     * Gets all users
     *
     * @return array
     */
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
