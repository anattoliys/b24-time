<?php

Class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($className) {
            $file = ROOT_DIR . '/classes/' . $className . '.php';

            if (file_exists($file)) {
                require_once $file;

                return true;
            }

            return false;
        });
    }
}
