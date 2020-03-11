<?php

class Tasks
{
    protected $userId = 108;

    protected function getMonthTasks()
    {
        $userId = $this->userId;
        $currentMonth = date('Y-m-01');
        $order = ['ID' => 'DESC'];
        $filter = ['RESPONSIBLE_ID' => $userId, '>=CREATED_DATE' => $currentMonth];
        $select = ['ID', 'TIME_ESTIMATE', 'DURATION_FACT'];

        $queryUrl = 'https://elize.bitrix24.ru/rest/' . $userId . '/du2g5fu92egn852b/task.item.list.json';
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
                $queryDataExt = http_build_query([
                    'ORDER' => $order,
                    'FILTER' => $filter,
                    'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => $i]],
                    'SELECT' => $select,
                ]);
    
                $curlExecExt = CurlQuery::exec($queryUrl, $queryDataExt);
    
                foreach($curlExecExt['result'] as $task) {
                    array_push($curlExec['result'], $task);
                }
    
                $curlExec['next'] = $curlExecExt['next'];
    
                $i++;
            }
        }

        return $curlExec['result'];
    }
}
