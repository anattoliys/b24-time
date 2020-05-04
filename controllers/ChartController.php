<?php

class ChartController
{
    public function actionIndex()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/views/chart/index.php';
        return true;
    }
}
