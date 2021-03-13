<?php

class MonthTime extends Tasks
{
    /**
     * Getting the time for the current month
     *
     * @return string
     */
    public function get($isConvertToHours = false)
    {
        $allMinutes = 0;

        if (!empty($this->getMonthTasks())) {
            foreach ($this->getMonthTasks() as $task) {
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
