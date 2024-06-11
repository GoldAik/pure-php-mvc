<?php

declare(strict_types = 1);

define('APP_PATH', __DIR__);
define('VIEW_PATH', __DIR__ . '/views');

require_once \APP_PATH . '/Router.php';
require_once \APP_PATH . '/App.php';

require_once \APP_PATH. '/Controlls/Home.php';

$router = new App\Router();

$router
    ->get('/', [App\Controlls\Home::class, 'home'])
    ->get('/home', [App\Controlls\Home::class, 'home'])
    ->get('/home-with-params', [App\Controlls\Home::class, 'homeWithParams']);

$app = new App\App($router);
$app->run();
