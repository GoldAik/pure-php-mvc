<?php

declare(strict_types = 1);

define('APP_PATH', __DIR__);
define('VIEW_PATH', __DIR__ . '/views');
define('LOG_PATH', __DIR__ . '/logs');
define('DOMAIN', '127.0.0.1');
define('DEBUG_MODE', true);

require_once APP_PATH . '/autoloader.php';

if(file_exists(\APP_PATH . '/ErrorHandler.php'))
    require_once \APP_PATH . '/ErrorHandler.php';
else{
    if(\DEBUG_MODE) throw new \RuntimeException('Unable to locate the ErrorHandler.php file');
    else exit;
}

new App\ErrorHandler();

$router = new App\Router();

$router
    ->get('/', [App\Controllers\Home::class, 'home'])
    ->get('/home', [App\Controllers\Home::class, 'home'])
    ->get('/home-with-params', [App\Controllers\Home::class, 'homeWithParams'])
    
    // display data send to get metod /get-request?m=
    ->get('/get-request', [App\Controllers\Home::class, 'getRequest'])
    ->get('/get-request-send-response', [App\Controllers\Home::class, 'getRequestSendResponse'])

    //json response
    ->get('/get-request-send-response-as-json', [App\Controllers\Home::class, 'getRequestSendJsonResponse'])

    //regex
    ->get('/value/{value}', [App\Controllers\Home::class, 'getValue'])
    ->get('/shelf/{<+int>:shelf_id}/book/{<+int>:book_id}', [App\Controllers\Home::class, 'getBook'])
    ->get('/negative-numbers/{<-int>:num}', [App\Controllers\Home::class, 'getNegativeNumber'])
    
    ->get('/get/users', [App\Controllers\User::class, 'getUsers'])

    //orm
    ->get('/users', [App\Controllers\User::class, 'getUsersModels'])
    ->get('/user/{<+int>:id}', [App\Controllers\User::class, 'getUser'])
    ->get('/delete/user/{<+int>:id}', [App\Controllers\User::class, 'deleteUser'])
    ->get('/edit/user/{<+int>:id}/username/{username}', [App\Controllers\User::class, 'editUsername'])
    ->get('/add/user/{username}', [App\Controllers\User::class, 'addUser'])

    //relations
    ->get('/user/{<+int>:id}/posts', [App\Controllers\Post::class, 'getUserPosts'])
    ->get('/user/{<+int>:id}/post/add', [App\Controllers\Post::class, 'addUserPost'])
    ->get('/post/{<+int>:id}/user', [App\Controllers\Post::class, 'getUserOfPost'])

    //view layout
    ->get('/posts', [App\Controllers\Post::class, 'getPosts'])
    
    //csrf tokens
    ->get('/user/{<+int>:userId}/posts/create', [App\Controllers\Post::class, 'create'])
    ->post('/user/{<+int>:userId}/posts/create', [App\Controllers\Post::class, 'postCreate'])
    
    // no view
    ->get('/no-view', [App\Controllers\Home::class, 'noView']);

$app = new App\App($router);
$app->run();
