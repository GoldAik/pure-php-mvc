<?php

declare(strict_types = 1);

namespace App;

use App\Http\Response;

class Router{

    private array $routes;
    private array $dynamicRoutes;

    private function register(string $requestMethod, string $path, callable|array $action): self
    {
        if(strpos($path, '{') === false)
            $this->routes[$requestMethod][$path] = $action;
        else 
            $this->dynamicRoutes[$requestMethod][] = ['pattern' => $path, 'action' => $action];
        return $this;
    }

    public function get(string $path, callable|array $action): self
    {
        return $this->register('get', $path, $action);
    }

    public function post(string $path, callable|array $action): self
    {
        return $this->register('post', $path, $action);
    }

    public function resolve(string $path, string $requestMethod, \App\Http\Request $request)
    {
        $action = $this->routes[$requestMethod][$path] ?? null;

        if($action)
            return $this->executeAction($action, $request);

        foreach ($this->dynamicRoutes[$requestMethod] ?? [] as $route) {
            if (preg_match($this->convertToRegex($route['pattern']), $path, $matches)) {
                array_shift($matches);
                return $this->executeAction($route['action'], $request, $matches);
            }
        }
    
        return Response::html('error 404', 404);
    }

    private function executeAction($action, $request, array $params = [])
    {
        if (is_callable($action)) {
            return call_user_func_array($action, $params);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class) && is_subclass_of($class, \App\Controllers\_Base\Controller::class)) {
                $instance = new $class($request);

                if (method_exists($instance, $method)) {
                    return call_user_func_array([$instance, $method], $params);
                }
            }
        }

        return Response::html('error 404', 404);
    }

    private function convertToRegex(string $subject): string
    {
        $patternsReplaces = [
            '<int>' => '(-?[0-9]+)',
            '<\+int>' => '([0-9]+)',
            '<-int>' => '(-[1-9]+[0-9]*)',
        ];

        foreach($patternsReplaces as $pattern => $replacement){
            $subject = preg_replace("/\{$pattern:[a-zA-Z_][a-zA-Z0-9_]*\}/", $replacement, $subject);
        }

        return '#^' . preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '([^/]+)', $subject) . '$#';
    }
}