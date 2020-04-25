<?php

class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class) {
            $directories = [
                '/classes/',
                '/models/',
            ];

            foreach ($directories as $directory) {
                $file = DOCUMENT_ROOT . $directory . $class . '.php';

                if (file_exists($file)) {
                    require_once $file;

                    return true;
                }
            }

            return false;
        });
    }
}
