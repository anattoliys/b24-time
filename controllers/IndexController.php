<?php

class IndexController
{
    public function actionIndex()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/views/index/index.php';
        return true;
    }
}
