<?php

class MonthTime
{
    public static function get()
    {
        $allMinutes = 0;
        $currentMonth = date('Y-m-01');

        $queryUrl = 'https://elize.bitrix24.ru/rest/108/du2g5fu92egn852b/task.item.list.json';

        $order = ['ID' => 'DESC'];
        $filter = ['RESPONSIBLE_ID' => 108, '>=CREATED_DATE' => $currentMonth];
        $select = ['ID', 'TIME_ESTIMATE', 'DURATION_FACT'];

        $queryData = http_build_query([
            'ORDER' => $order,
            'FILTER' => $filter,
            'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => 1]],
            'SELECT' => $select,
        ]);

        $curlExec = CurlQuery::exec($queryUrl, $queryData);

        if($curlExec['next'] < $curlExec['total']) {
            $totalPages = intval($curlExec['total'] / 50 + 1);

            for($i = 2; $i <= $totalPages; $i++) {
                $queryData = http_build_query([
                    'ORDER' => $order,
                    'FILTER' => $filter,
                    'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => $i]],
                    'SELECT' => $select,
                ]);
    
                $curlExecExt = CurlQuery::exec($queryUrl, $queryData);
    
                foreach($curlExecExt['result'] as $task) {
                    array_push($curlExec['result'], $task);
                }
    
                $curlExec['next'] = $curlExecExt['next'];
    
                $i++;
            }
        }

        if(!empty($curlExec['result'])) {
            foreach($curlExec['result'] as $task) {
                $timeEstimate = intval($task['TIME_ESTIMATE']) / 60;
                $durationFact = intval($task['DURATION_FACT']);

                if($timeEstimate > 0) {
                    $allMinutes += $timeEstimate;
                } else {
                    $allMinutes += $durationFact;
                }
            }
        }

        if($allMinutes > 0) {
            $allMinutes = ConvertMinutes::exec($allMinutes);
        }

        return $allMinutes;
    }
}
