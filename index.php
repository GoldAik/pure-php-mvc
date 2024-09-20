<?php

declare(strict_types = 1);

define('APP_PATH', __DIR__);
define('VIEW_PATH', __DIR__ . '/views');
define('DOMAIN', '127.0.0.1');

require_once \APP_PATH . '/Router.php';
require_once \APP_PATH . '/App.php';

require_once \APP_PATH. '/Controlls/Controlls.php';
require_once \APP_PATH. '/Controlls/Home.php';

$router = new App\Router();

$router
    ->get('/', [App\Controlls\Home::class, 'home'])
    ->get('/home', [App\Controlls\Home::class, 'home'])
    ->get('/home-with-params', [App\Controlls\Home::class, 'homeWithParams'])
    
    // display data send to get metod /get-request?m=
    ->get('/get-request', [App\Controlls\Home::class, 'getRequest'])
    ->get('/get-request-send-response', [App\Controlls\Home::class, 'getRequestSendResponse'])

    //json response
    ->get('/get-request-send-response-as-json', [App\Controlls\Home::class, 'getRequestSendJsonResponse'])

    //regex
    ->get('/user/{id}', [App\Controlls\Home::class, 'getUser'])
    ->get('/shelf/{<+int>:shelf_id}/book/{<+int>:book_id}', [App\Controlls\Home::class, 'getBook'])
    ->get('/negative-numbers/{<-int>:num}', [App\Controlls\Home::class, 'getNegativeNumber'])
    
    ->get('/get/users', [App\Controlls\Home::class, 'getUsers'])
    
    // no view
    ->get('/no-view', [App\Controlls\Home::class, 'noView']);

$app = new App\App($router);
$app->run();
