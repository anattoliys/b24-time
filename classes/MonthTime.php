<?php

class MonthTime extends Tasks
{
    public function get()
    {
        $allMinutes = 0;

        if(!empty($this->getMonthTasks())) {
            foreach($this->getMonthTasks() as $task) {
                $timeEstimate = intval($task['TIME_ESTIMATE']) / 60;
                $durationFact = intval($task['DURATION_FACT']);

                if($timeEstimate > 0) {
                    $allMinutes += $timeEstimate;
                } else {
                    $allMinutes += $durationFact;
                }
            }
        }

        if($allMinutes > 0) {
            $allMinutes = ConvertMinutes::exec($allMinutes);
        }

        return $allMinutes;
    }
}
