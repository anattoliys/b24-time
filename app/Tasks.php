<?php

class Tasks
{
    /**
     * Getting all tasks for the current month
     *
     * @param array $user
     * @return array
     */
    protected function getMonthTasks($user)
    {
        $currentMonth = date('Y-m-01');
        $order = ['ID' => 'DESC'];
        $select = ['ID', 'TIME_ESTIMATE', 'DURATION_FACT'];
        $filter['>=CREATED_DATE'] = $currentMonth;

        if ($user['position'] == 'manager') {
            $filter['CREATED_BY'] = $user['b24Id'];
        } else {
            $filter['RESPONSIBLE_ID'] = $user['b24Id'];
        }

        $queryUrl = B24_WEBHOOK . 'task.item.list.json';
        $queryData = [
            'ORDER' => $order,
            'FILTER' => $filter,
            'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => 1]],
            'SELECT' => $select,
        ];

        $curlExec = CurlQuery::exec($queryUrl, $queryData);

        $next = isset($curlExec['next']) ? $curlExec['next'] : 0;

        if ($next < $curlExec['total']) {
            $totalPages = intval($curlExec['total'] / 50 + 1);

            for ($i = 2; $i <= $totalPages; $i++) {
                $queryDataExt = [
                    'ORDER' => $order,
                    'FILTER' => $filter,
                    'PARAMS' => ['NAV_PARAMS' => ['nPageSize' => 50, 'iNumPage' => $i]],
                    'SELECT' => $select,
                ];

                $curlExecExt = CurlQuery::exec($queryUrl, $queryDataExt);

                $nextExt = isset($curlExecExt['next']) ? $curlExecExt['next'] : 0;

                foreach ($curlExecExt['result'] as $task) {
                    array_push($curlExec['result'], $task);
                }

                $next = $nextExt;

                $i++;
            }
        }

        return $curlExec['result'];
    }
}
