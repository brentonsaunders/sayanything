<?php
define('ROOT_DIR', realpath(__DIR__ . '/..'));
define("PUBLIC_HTML", "http://localhost/sayanything/public/www");

spl_autoload_register(function($className) {
    $parts = explode('\\', $className);

    $className = array_pop($parts);

    $parts = array_map('strtolower', $parts);

    $path = implode(DIRECTORY_SEPARATOR, $parts);

    $file = __DIR__ . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $className . '.php';

    if(file_exists($file)) {
        require $file;
    }
});