<?php

declare(strict_types = 1);

namespace App;

require_once \APP_PATH . '/Http/View.php';
require_once \APP_PATH . '/Http/Request.php';

use App\Router;
use App\Http\View;
use App\Http\Request;

class App{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function run()
    {
        try{
            $request = new Request();
            $uri = Request::getUri();
            $method = Request::getMethod();
            echo $this->router->resolve($uri, $method, $request);
        }catch(\Throwable $e){
            // echo $e for development usage
            echo $e;
            echo View::make('errors/404');
        }
    }
}