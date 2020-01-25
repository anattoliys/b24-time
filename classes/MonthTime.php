<?php

class MonthTime
{
    public static function get()
    {
        $all_minutes = 0;
        $current_month = date("Y-m-01");

        $query_url = "https://elize.bitrix24.ru/rest/108/du2g5fu92egn852b/task.item.list.json";

        $query_data = http_build_query(array(
            "ORDER" => array("ID" => "DESC"),
            "FILTER" => array("RESPONSIBLE_ID" => "108", ">=CREATED_DATE" => $current_month),
            "PARAMS" => array("NAV_PARAMS" => array()),
            "SELECT" => array(),
        ));

        $curl_exec = CurlQuery::exec($query_url, $query_data);

        if(!empty($curl_exec["result"])) {
            foreach($curl_exec["result"] as $task) {
                if(!empty($task["TIME_ESTIMATE"])) {
                    $all_minutes += intval($task["TIME_ESTIMATE"]) / 60;
                } else {
                    $all_minutes += intval($task["DURATION_FACT"]);
                }
            }
        }

        if($all_minutes > 0) {
            $all_minutes = ConvertMinutes::exec($all_minutes);
        }

        return $all_minutes;
    }
}
