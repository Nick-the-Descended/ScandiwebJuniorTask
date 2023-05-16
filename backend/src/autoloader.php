<?php

//spl_autoload_register(function ($className) {
//    $array_paths = array(
//        'Classes' . DIRECTORY_SEPARATOR,
//        'Classes' . DIRECTORY_SEPARATOR . 'ProductTypes'
//    );
//
//    $class_name = $className;
//    foreach($array_paths as $path)
//    {
//        $file = sprintf('src' . DIRECTORY_SEPARATOR . '%s%s.php', $path, $class_name);
//        if(is_file($file))
//        {
//            include_once $file;
//        }
//    }
//});

spl_autoload_register(function ($className) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($file)) {
        require_once($file);
    }
});