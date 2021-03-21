<?php

class DayTime extends Tasks
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Getting the time for today
     *
     * @return string
     */
    public function get()
    {
        $allMinutes = 0;
        $monthTasks = $this->getMonthTasks($this->userId);

        if (!empty($monthTasks)) {
            $taskId = array_column($monthTasks, 'ID');
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
