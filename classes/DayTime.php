<?php

class DayTime
{
    protected $userId = 108;

    public function get()
    {
        $userId = $this->userId;
        $taskId = [];
        $allMinutes = 0;
        $currentDate = date('Y-m-d');
        $currentMonth = date('Y-m-01');

        $arrOrder = ['ID' => 'DESC'];
        $arrFilter = ['RESPONSIBLE_ID' => $userId, '>=CREATED_DATE' => $currentMonth];
        $arrSelect = ['ID'];

        $queryTaskUrl = 'https://elize.bitrix24.ru/rest/' . $userId . '/du2g5fu92egn852b/task.item.list.json';
        $queryTaskData = http_build_query([
            'ORDER' => $arrOrder,
            'FILTER' => $arrFilter,
            'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => 1]],
            'SELECT' => $arrSelect,
        ]);

        $curlTaskExec = CurlQuery::exec($queryTaskUrl, $queryTaskData);

        if($curlTaskExec['next'] < $curlTaskExec['total']) {
            $totalPages = intval($curlTaskExec['total'] / 50 + 1);

            for($i = 2; $i <= $totalPages; $i++) {
                $queryTaskDataExt = http_build_query([
                    'ORDER' => $arrOrder,
                    'FILTER' => $arrFilter,
                    'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => $i]],
                    'SELECT' => $arrSelect,
                ]);
    
                $curlTaskExecExt = CurlQuery::exec($queryTaskUrl, $queryTaskDataExt);
    
                foreach($curlTaskExecExt['result'] as $task) {
                    array_push($curlTaskExec['result'], $task);
                }
    
                $curlTaskExec['next'] = $curlTaskExecExt['next'];
    
                $i++;
            }
        }

        $taskId = array_column($curlTaskExec['result'], 'ID');

        if (!empty($taskId)) {
            $queryTimeUrl = 'https://elize.bitrix24.ru/rest/' . $userId . '/du2g5fu92egn852b/task.elapseditem.getlist.json';
        
            $queryTimeData = http_build_query([
                'ORDER' => ['ID' => 'DESC'],
                'FILTER' => ['USER_ID' => $userId, 'TASK_ID' => $taskId, '>=CREATED_DATE' => $currentDate],
                'SELECT' => ['ID', 'TASK_ID', 'MINUTES'],
                'PARAMS' => [],
            ]);

            $curlTimeExec = CurlQuery::exec($queryTimeUrl, $queryTimeData);

            if(!empty($curlTimeExec['result'])) {
                foreach($curlTimeExec['result'] as $time) {
                    $allMinutes += intval($time['MINUTES']);
                }
            }

            if($allMinutes > 0) {
                $allMinutes = ConvertMinutes::exec($allMinutes);
            }
        }
        
        return $allMinutes;
    }
}
