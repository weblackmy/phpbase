<?php
require __DIR__ . '/vendor/autoload.php';
spl_autoload_register(function($class) {
    if (strpos($class, '\\') === false) {
        return false;
    }
    $class = str_replace('\\', '/', $class) . '.php';
    $class = __DIR__ . substr($class, strpos($class, '/'));
    if (is_file($class)) {
        require $class;
        return true;
    }
	throw new \Exception("class {$class} does't exist");
});