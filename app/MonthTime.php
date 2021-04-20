<?php

namespace app;

class MonthTime extends Tasks
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Getting the time for the current month
     *
     * @return string
     */
    public function get()
    {
        $allMinutes = 0;
        $monthTasks = $this->getMonthTasks($this->user);

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

        return $allMinutes;
    }
}
