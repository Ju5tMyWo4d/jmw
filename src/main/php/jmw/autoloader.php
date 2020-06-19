<?php


spl_autoload_register(function($className) {
    $dirs = explode('\\', $className);
    array_shift($dirs);
    $path = __DIR__.DIRECTORY_SEPARATOR.implode( DIRECTORY_SEPARATOR, $dirs).'.php';

    include_once $path;
}, true, true);