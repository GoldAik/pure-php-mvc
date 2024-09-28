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
            set_error_handler([$this, "warningHandler"]);

            $request = new Request();
            $uri = Request::getUri();
            $method = Request::getMethod();
            echo $this->router->resolve($uri, $method, $request);
        }catch(\Throwable $e){
            Log::throwable($e);

            if(\DEBUG_MODE)
                echo $e;
            else
                echo '404';
        }
    }

    public function warningHandler($errno, $errstr, $errfile, $errline)
    {
        if($errno == E_WARNING) {
            Log::warning($errstr);

            if(\DEBUG_MODE)
                echo $e;
            else
                return true;
        }

        return false; 
    }
}