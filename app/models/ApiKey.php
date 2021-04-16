<?php

class ApiKey
{
    /**
     * Returns api key
     *
     * @param string $keyName
     * @return string
     */
    public static function getKey($keyName)
    {
        $keyValue = '';
        $db = Db::connect();
        $sql = "SELECT * FROM apiKey WHERE keyName = '$keyName' LIMIT 1";
        $query = $db->query($sql, PDO::FETCH_ASSOC);

        foreach ($query as $row) {
            $keyValue = $row['keyValue'];
        }

        $query = null;
        $db = null;

        return $keyValue;
    }
}
