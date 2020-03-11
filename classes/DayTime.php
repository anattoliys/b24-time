<?php

class DayTime extends Tasks
{
    public function get()
    {
        if (!empty($this->getMonthTasks())) {
            $taskId = [];
            $allMinutes = 0;
            $currentDate = date('Y-m-d');

            $queryTimeUrl = 'https://elize.bitrix24.ru/rest/' . $this->userId . '/du2g5fu92egn852b/task.elapseditem.getlist.json';
        
            $queryTimeData = http_build_query([
                'ORDER' => ['ID' => 'DESC'],
                'FILTER' => ['USER_ID' => $this->userId, 'TASK_ID' => $taskId, '>=CREATED_DATE' => $currentDate],
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
