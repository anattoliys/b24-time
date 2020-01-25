<?php

class DayTime
{
    public static function get()
    {
        $all_minutes = 0;
        $current_date = date("Y-m-d");

        $query_url = "https://elize.bitrix24.ru/rest/108/du2g5fu92egn852b/task.elapseditem.getlist.json";
    
        $query_data = http_build_query(array(
            "ORDER" => array("ID" => "DESC"),
            "FILTER" => array("USER_ID" => "108", ">=CREATED_DATE" => $current_date),
            "SELECT" => array("ID", "TASK_ID", "MINUTES"),
            "PARAMS" => array(),
        ));

        $curl_exec = CurlQuery::exec($query_url, $query_data);

        if(!empty($curl_exec["result"])) {
            foreach($curl_exec["result"] as $time) {
                $all_minutes += intval($time["MINUTES"]);
            }
        }

        if($all_minutes > 0) {
            $all_minutes = ConvertMinutes::exec($all_minutes);
        }

        return $all_minutes;
    }
}
