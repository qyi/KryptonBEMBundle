<?php

spl_autoload_register(function ($class) {
    if (0 === strpos(ltrim($class, '/'), 'Krypton\BEMBundle')) {
        if (file_exists($file = __DIR__.'/../../'.substr(str_replace('\\', '/', $class), strlen('Krypton\BEMBundle')).'.php')) {
            require_once $file;
        }
    }
});

$loader = require __DIR__.'/../../vendor/autoload.php';
require_once __DIR__ . '/../../Controller/Annotations/BEM.php';

return $loader;