<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

spl_autoload_register(function ($class) {
    $directories = [
        '/app/',
        '/app/core/',
        '/app/models/',
    ];

    foreach ($directories as $directory) {
        $file = $_SERVER['DOCUMENT_ROOT'] . $directory . $class . '.php';

        if (file_exists($file)) {
            require_once $file;

            return true;
        }
    }

    return false;
});
