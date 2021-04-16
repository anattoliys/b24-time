<?php

namespace app\controllers;

class IndexController
{
    /**
     * Including the main page file
     *
     * @return bool
     */
    public function actionIndex()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/views/index/index.php';
        return true;
    }
}
