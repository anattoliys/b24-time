<?php

namespace app\controllers;

class ChartController
{
    /**
     * Including the chart file
     *
     * @return bool
     */
    public function actionIndex()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/views/chart/index.php';
        return true;
    }
}
