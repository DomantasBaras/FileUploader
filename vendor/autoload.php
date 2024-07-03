<?php
// Autoloader function to follow PSR-4 standards
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // If the class does not use the namespace prefix, return
        return;
    }
    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php'; 

    if (file_exists($file)) {
        // If the file exists, require it
        require $file;
    }
});
