<?php

return function (string $prefix = 'App\\', string $dir = 'src/'): void {
    \spl_autoload_register(function ($class) use ($prefix, $dir): bool {
        $prefixPattern = '/^' . \preg_quote($prefix) . '/';
        
        $class = \preg_replace($prefixPattern, $dir, $class);
        
        $path = \str_replace('\\', '/', $class) . '.php';
        $absolutePath = __DIR__ . '/' . $path;

        if (! \file_exists($absolutePath)) {
            return false;
        }

        require_once $absolutePath;
        return true;
    });
};