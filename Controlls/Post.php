<?php

declare(strict_types = 1);

namespace App\Controlls;

require_once \APP_PATH . '/Http/JsonResponse.php';
require_once \APP_PATH . '/Http/View.php';

require_once \APP_PATH . '/Models/UserModel.php';
require_once \APP_PATH . '/Models/PostModel.php';

use App\Controlls\_Base\Controlls;

use App\Http\JsonResponse;
use App\Http\View;

use App\Models\UserModel;
use App\Models\PostModel;


class Post extends Controlls{
    public function getUserPosts($userId): JsonResponse
    {
        $user = UserModel::find($userId);
        $posts = [];
        if($user){
            $posts = $user->posts();
        }

        $data = [
            'user' => $user,
            'posts' => $posts,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function addUserPost($userId): JsonResponse
    {
        $user = UserModel::find($userId);
        $post = new PostModel;
        $success = false;

        if($user){
            $post->content = 'Post content';
            $post->user_id = $user->id;
            $success = $post->save();
        }
        
        $data = [
            'user' => $user,
            'post' => $post,
            'success' => $success,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function getUserOfPost($postId): JsonResponse
    {
        $post = PostModel::find($postId);
        $user = null;
        if($post){
            $user = $post->user();
        }

        $data = [
            'post' => $post,
            'user' => $user,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function getPosts(): View
    {
        $posts = PostModel::all();

        $data = [
            'posts' => $posts,
            'timestamp' => time(),
        ];

        return View::make('posts/home', ['data' => $data]);
    }
}