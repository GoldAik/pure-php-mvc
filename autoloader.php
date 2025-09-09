<?php

\spl_autoload_register(function ($class): bool {
    $prefixNamespace = 'App\\';

    if (\str_starts_with($class, $prefixNamespace)) {
        $class = \substr($class, \strlen($prefixNamespace));
    }
    
    $path = \str_replace('\\', '/', $class) . '.php';
    $absolutePath = __DIR__ . '/' . $path;

    if (! \file_exists($absolutePath)) {
        return false;
    }

    require_once $absolutePath;
    return true;
});