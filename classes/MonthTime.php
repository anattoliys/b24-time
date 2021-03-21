<?php

class MonthTime extends Tasks
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Getting the time for the current month
     *
     * @return string
     */
    public function get($isConvertToHours = false)
    {
        $allMinutes = 0;
        $monthTasks = $this->getMonthTasks($this->userId);

        if (!empty($monthTasks)) {
            foreach ($monthTasks as $task) {
                $timeEstimate = intval($task['TIME_ESTIMATE']) / 60;
                $durationFact = intval($task['DURATION_FACT']);

                if ($timeEstimate > 0) {
                    $allMinutes += $timeEstimate;
                } else {
                    $allMinutes += $durationFact;
                }
            }
        }

        if ($allMinutes > 0 && $isConvertToHours) {
            $allMinutes = ConvertMinutes::exec($allMinutes);
        }

        return $allMinutes;
    }
}
