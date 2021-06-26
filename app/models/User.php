<?php

namespace app\models;

use \PDO;
use app\core\Db;
use app\DayTime;
use app\MonthTime;
use app\utils\Converter;

class User
{
    /**
     * Сreates a new user
     *
     * @param integer $chatId
     * @param string $name
     * @param integer $updateId
     * @return array
     */
    public function create($chatId, $name, $updateId)
    {
        $date = date('Y-m-d G:i:s');

        $db = Db::connect();
        $db->query("SET character_set_client='utf8mb4'");
        $db->query("SET collation_connection='utf8mb4_general_ci'");
        $sql = 'INSERT INTO users (name, chatId, updateId, creationDate) VALUES (?, ?, ?, ?)';

        $query = $db->prepare($sql);
        $user = $query->execute([$name, $chatId, $updateId, $date]);

        $query = null;
        $db = null;

        return $user;
    }

    /**
     * Gets the update id
     *
     * @param integer $chatId
     * @return integer
     */
    public static function getUpdateId($chatId)
    {
        $updateId = 0;
        $users = [];

        $db = Db::connect();
        $sql = "SELECT id, updateId FROM users WHERE chatId = '$chatId'";

        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $users[] = $row;
        }

        $updateId = isset(end($users)['updateId']) ? end($users)['updateId'] : 0;

        $query = null;
        $db = null;

        return $updateId;
    }

    /**
     * Gets user id
     *
     * @param integer $chatId
     * @return array
     */
    public static function getId($chatId)
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
     * Updates user
     *
     * @param integer $userId
     * @param array $fields
     * @return bool
     */
    public function update($userId, $fields)
    {
        $result = false;

        if (!empty($fields)) {
            $keys = array_keys($fields);

            foreach ($keys as $key => $val) {
                $keys[$key] = $val . ' = ?';
            }

            $keys = implode(', ', $keys);
            $vals = implode(', ', $fields);

            $db = Db::connect();
            $sql = 'UPDATE users SET ' . $keys . ' WHERE id = ?';

            $query = $db->prepare($sql);
            $result = $query->execute([$vals, $userId]);

            $query = null;
            $db = null;
        }

        return $result;
    }

    /**
     * Gets users by filter
     *
     * @param array $filter
     * @return array
     */
    public function getList($filter = [], $getTime = false)
    {
        $users = [];
        $sqlFilter = '';

        if (!empty($filter)) {
            $i = 0;
            $sqlFilter .= ' WHERE ';

            foreach ($filter as $key => $val) {
                if ($i > 0) {
                    $sqlFilter .= ' AND ';
                }

                if (strpos($key, '!') !== false) {
                    $key = str_replace('!', 'NOT ', $key);
                }

                $sqlFilter .= "$key = '$val'";

                $i++;
            }
        }

        $db = Db::connect();
        $sql = 'SELECT * FROM users' . $sqlFilter;

        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $users[] = $row;
        }

        if (!empty($users) && $getTime) {
            foreach ($users as $key => $user) {
                $users[$key] = $this->getTime($user);
            }
        }

        $query = null;
        $db = null;

        return $users;
    }

    /**
     * Gets user time
     *
     * @param array $user
     * @return array
     */
    private function getTime($user)
    {
        $dayTime = new DayTime($user);
        $user['dayTimeSeconds'] = $dayTime->get();

        $monthTime = new MonthTime($user);
        $user['monthTimeSeconds'] = $monthTime->get();

        $user['dayTime'] = Converter::minutesByFormat($user['dayTimeSeconds']);
        $user['monthTime'] = Converter::minutesByFormat($user['monthTimeSeconds']);
        $user['money'] = number_format($user['monthTimeSeconds'] * $user['rate'] / 60, 0, '.', ' ');

        return $user;
    }
}
