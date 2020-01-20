<?php

$current_date = date("Y-m-d");

$queryUrl = "https://elize.bitrix24.ru/rest/108/du2g5fu92egn852b/task.elapseditem.getlist.json";

$queryData = http_build_query(array(
    "ORDER" => array("ID" => "DESC"),
    "FILTER" => array("USER_ID" => "108", ">=CREATED_DATE" => $current_date),
    "SELECT" => array("ID", "TASK_ID", "MINUTES"),
    "PARAMS" => array(),
));

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_POST => 1,
    CURLOPT_HEADER => 0,
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $queryUrl,
    CURLOPT_POSTFIELDS => $queryData,
));

$result = curl_exec($curl);
curl_close($curl);

$result = json_decode($result, 1);
