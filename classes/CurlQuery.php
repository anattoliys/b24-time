<?php

Class CurlQuery
{
    public static function exec($queryUrl, $queryData)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $queryUrl,
            CURLOPT_POSTFIELDS => $queryData,
        ]);
    
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, 1);

        return $result;
    }
}
