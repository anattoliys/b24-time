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
        $select = ['ID'];

        $queryTaskUrl = 'https://elize.bitrix24.ru/rest/' . $userId . '/du2g5fu92egn852b/task.item.list.json';
        $queryTaskData = http_build_query([
            'ORDER' => $order,
            'FILTER' => $filter,
            'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => 1]],
            'SELECT' => $select,
        ]);

        $curlTaskExec = CurlQuery::exec($queryTaskUrl, $queryTaskData);

        if($curlTaskExec['next'] < $curlTaskExec['total']) {
            $totalPages = intval($curlTaskExec['total'] / 50 + 1);

            for($i = 2; $i <= $totalPages; $i++) {
                $queryTaskDataExt = http_build_query([
                    'ORDER' => $order,
                    'FILTER' => $filter,
                    'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => $i]],
                    'SELECT' => $select,
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

        return $taskId;
    }
}
