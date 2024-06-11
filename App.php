<?php

declare(strict_types = 1);

namespace App;

require_once \APP_PATH . '/View.php';
require_once \APP_PATH . '/Request.php';

use App\Router;
use App\View;
use App\Request;

class App{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        try{
            $uri = Request::getUri();
            $method = Request::getMethod();
            echo $this->router->resolve($uri, $method);
        }catch(\Throwable $e){
            echo $e;
            echo View::make('errors/404');
        }
    }
}