<?php

class DayTime extends Tasks
{
    public function get()
    {
        $allMinutes = 0;

        if (!empty($this->getMonthTasks())) {
            $taskId = array_column($this->getMonthTasks(), 'ID');
            $currentDate = date('Y-m-d');

            $queryUrl = 'https://elize.bitrix24.ru/rest/' . $this->userId . '/du2g5fu92egn852b/task.elapseditem.getlist.json';

            $queryData = http_build_query([
                'ORDER' => ['ID' => 'DESC'],
                'FILTER' => ['TASK_ID' => $taskId, '>=CREATED_DATE' => $currentDate],
                'SELECT' => ['ID', 'TASK_ID', 'MINUTES'],
                'PARAMS' => [],
            ]);

            $curlExec = CurlQuery::exec($queryUrl, $queryData);

            if (!empty($curlExec['result'])) {
                foreach ($curlExec['result'] as $time) {
                    $allMinutes += intval($time['MINUTES']);
                }
            }

            if ($allMinutes > 0) {
                $allMinutes = ConvertMinutes::exec($allMinutes);
            }
        }

        return $allMinutes;
    }
}
