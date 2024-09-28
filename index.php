<?php

declare(strict_types = 1);

define('APP_PATH', __DIR__);
define('VIEW_PATH', __DIR__ . '/views');
define('DOMAIN', '127.0.0.1');

require_once \APP_PATH . '/Router.php';
require_once \APP_PATH . '/App.php';

require_once \APP_PATH. '/Controlls/_Base/Controlls.php';
require_once \APP_PATH. '/Controlls/Home.php';
require_once \APP_PATH. '/Controlls/User.php';
require_once \APP_PATH. '/Controlls/Post.php';

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
    
    ->get('/get/users', [App\Controlls\User::class, 'getUsers'])

    //orm
    ->get('/get/users-models', [App\Controlls\User::class, 'getUsersModels'])
    ->get('/get/user/{<+int>:id}', [App\Controlls\User::class, 'getUser'])
    ->get('/delete/user/{<+int>:id}', [App\Controlls\User::class, 'deleteUser'])
    ->get('/edit/user/{<+int>:id}/username/{username}', [App\Controlls\User::class, 'editUsername'])
    ->get('/add/user/{username}', [App\Controlls\User::class, 'addUser'])

    //relations
    ->get('/user/{<+int>:id}/posts', [App\Controlls\Post::class, 'getUserPosts'])
    ->get('/user/{<+int>:id}/post/add', [App\Controlls\Post::class, 'addUserPost'])
    ->get('/post/{<+int>:id}/user', [App\Controlls\Post::class, 'getUserOfPost'])

    //view layout
    ->get('/posts', [App\Controlls\Post::class, 'getPosts'])
    
    // no view
    ->get('/no-view', [App\Controlls\Home::class, 'noView']);

$app = new App\App($router);
$app->run();
