<?php

class MonthTime
{
    public static function get()
    {
        $all_minutes = 0;
        $current_month = date("Y-m-01");

        $query_url = "https://elize.bitrix24.ru/rest/108/du2g5fu92egn852b/task.item.list.json";

        $arr_order = array("ID" => "DESC");
        $arr_filter = array("RESPONSIBLE_ID" => "108", ">=CREATED_DATE" => $current_month);
        $arr_select = array("ID", "TIME_ESTIMATE", "DURATION_FACT");

        $query_data = http_build_query(array(
            "ORDER" => $arr_order,
            "FILTER" => $arr_filter,
            "PARAMS" => array("NAV_PARAMS" => array("nPageSize" => 50, "iNumPage" => 1)),
            "SELECT" => $arr_select,
        ));

        $curl_exec = CurlQuery::exec($query_url, $query_data);

        if($curl_exec["next"] < $curl_exec["total"]) {
            $total_pages = intval($curl_exec["total"] / 50 + 1);

            for($i = 2; $i <= $total_pages; $i++) {
                $query_data = http_build_query(array(
                    "ORDER" => $arr_order,
                    "FILTER" => $arr_filter,
                    "PARAMS" => array("NAV_PARAMS" => array("nPageSize" => 50, "iNumPage" => $i)),
                    "SELECT" => $arr_select,
                ));
    
                $curl_exec_ext = CurlQuery::exec($query_url, $query_data);
    
                foreach($curl_exec_ext["result"] as $task) {
                    array_push($curl_exec["result"], $task);
                }
    
                $curl_exec["next"] = $curl_exec_ext["next"];
    
                $i++;
            }
        }

        if(!empty($curl_exec["result"])) {
            foreach($curl_exec["result"] as $task) {
                $time_estimate = intval($task["TIME_ESTIMATE"]) / 60;
                $duration_fact = intval($task["DURATION_FACT"]);

                if($time_estimate > 0) {
                    $all_minutes += $time_estimate;
                } else {
                    $all_minutes += $duration_fact;
                }
            }
        }

        if($all_minutes > 0) {
            $all_minutes = ConvertMinutes::exec($all_minutes);
        }

        return $all_minutes;
    }
}
