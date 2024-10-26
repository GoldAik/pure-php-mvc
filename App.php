<?php

declare(strict_types = 1);

namespace App;

require_once \APP_PATH . '/Http/Response.php';
require_once \APP_PATH . '/Http/View.php';
require_once \APP_PATH . '/Http/Request.php';
require_once \APP_PATH . '/Http/Session.php';

require_once \APP_PATH . '/Log.php';

use App\Router;
use App\Log;

use App\Http\View;
use App\Http\Request;
use App\Http\Session;
use Error;

class App{
    private Router $router;
    private Session $session;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->session = new Session();
    }

    public function run()
    {
        try{
            $request = new Request();
            $uri = Request::getUri();
            $method = Request::getMethod();
            
            echo $this->router->resolve($uri, $method, $request);

        }catch(\Throwable $e){
            $err = new \RuntimeException('An error occurred during request processing. ' . $e->getMessage(), 0, $e);
            
            if(\DEBUG_MODE)
                throw $err;
            else
                Log::throwable($err);

            echo '404';
        }
    }
}