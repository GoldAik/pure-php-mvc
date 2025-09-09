<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\_Base\Controller;

use App\Http\JsonResponse;
use App\Http\View;

use App\Models\UserModel;
use App\Models\PostModel;


use App\Utils\CSRFTokenHandler;


class Post extends Controller{
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

        return JsonResponse::make($data, statusCode: 200);
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

        return JsonResponse::make($data, statusCode: 200);
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

        return JsonResponse::make($data, statusCode: 200);
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

    public function create(int $userId): View
    {
        return View::make('posts/create');
    }

    public function postCreate(int $userId): View
    {
        $r = $this->request;
        $csrfToken = $r->postAndUnset(CSRFTokenHandler::HTML_KEY_NAME);
        if(!CSRFTokenHandler::isValid($csrfToken)) return View::make('errors/invalid-token');

        $post = new PostModel;
        $post->content = $r->postAndUnset('content');
        $post->user_id = $userId;
        $success = $post->save();

        $data = [
            'success' => $success,
            'content' => $r->postAndUnset('content'),
        ];
        
        return View::make('posts/create', ['data' => $data]);
    }
}