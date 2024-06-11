<?php

declare(strict_types = 1);

namespace App;

use App\View;

class Router{

    private array $routes;

    private function register(string $requestMethod, string $path, callable|array $action): self
    {
        $this->routes[$requestMethod][$path] = $action;

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

    public function resolve(string $path, string $requestMethod)
    {
        $action = $this->routes[$requestMethod][$path] ?? null;

        if(!$action){
            return View::make('errors/404');
        }

        if(is_callable($action)){
            return call_user_func($action);
        }

        if(is_array($action)){
            [$class, $method] = $action;

            if(class_exists($class)){
                $class = new $class();

                if(method_exists($class, $method)){
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        return View::make('errors/404');
    }
}