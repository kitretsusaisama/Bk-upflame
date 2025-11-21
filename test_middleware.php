<?php

require_once 'vendor/autoload.php';

try {
    $class = 'App\Http\Middleware\TenantResolution';
    if (class_exists($class)) {
        echo "Class $class exists\n";
        $reflection = new ReflectionClass($class);
        echo "Class file: " . $reflection->getFileName() . "\n";
    } else {
        echo "Class $class does not exist\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}