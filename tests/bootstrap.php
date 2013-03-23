<?php

if (file_exists($alFile = __DIR__.'/../vendor/autoload.php')) { 
    require($alFile);
} elseif (file_exists($alFile = __DIR__.'/../autoload.php')) {
    require($alFile);
} elseif (file_exists($alFile = __DIR__.'/../../../autoload.php')) {
    require($alFile);
}

spl_autoload_register(function ($class) {
    if (0 === strpos(ltrim($class, '/'), 'GeneratorTest')) {
        if (file_exists($file = __DIR__.'/'.str_replace('\\', '/', $class).'.php')) {
            require_once $file;
        }
    }
});
