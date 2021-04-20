<?php

namespace app;

use app\utils\CurlQuery;

class DayTime extends Tasks
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Getting the time for today
     *
     * @return string
     */
    public function get()
    {
        $allMinutes = 0;
        $monthTasks = $this->getMonthTasks($this->user);

        if (!empty($monthTasks)) {
            $taskId = array_column($monthTasks, 'ID');
            $currentDate = date('Y-m-d');

            $queryUrl = B24_WEBHOOK . 'task.elapseditem.getlist.json';

            $queryData = [
                'ORDER' => ['ID' => 'DESC'],
                'FILTER' => ['TASK_ID' => $taskId, '>=CREATED_DATE' => $currentDate],
                'SELECT' => ['ID', 'TASK_ID', 'MINUTES'],
                'PARAMS' => [],
            ];

            $curlExec = CurlQuery::exec($queryUrl, $queryData);

            if (!empty($curlExec['result'])) {
                foreach ($curlExec['result'] as $time) {
                    $allMinutes += intval($time['MINUTES']);
                }
            }
        }

        return $allMinutes;
    }
}
