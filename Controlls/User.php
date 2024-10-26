<?php

declare(strict_types = 1);

namespace App\Controlls;

require_once \APP_PATH . '/Http/JsonResponse.php';
require_once \APP_PATH . '/Models/User.php';
require_once \APP_PATH . '/Models/UserModel.php';

use App\Controlls\_Base\Controlls;

use App\Http\JsonResponse;

use App\Models\User as UserDB;
use App\Models\UserModel;

class User extends Controlls{
    public function getUsers(): JsonResponse
    {
        $userDB = new UserDB();
        $users = $userDB->getAllUsers();

        $data = [
            'users' => $users,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }

    public function getUsersModels(): JsonResponse
    {
        $users = UserModel::all();

        $data = [
            'users' => $users,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }

    public function getUser($id): JsonResponse
    {
        $user = UserModel::find($id);

        $data = [
            'user' => $user,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }

    public function deleteUser($id): JsonResponse
    {
        $user = UserModel::find($id);
        $success = false;

        if($user) 
            $success = $user->delete();

        $data = [
            'success' => $success,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }

    public function editUsername($id, $username): JsonResponse
    {
        $user = UserModel::find($id);
        $success = false;

        if($user){
            $user->username = $username;
            $success = $user->save();

            $errors = $user->getValidationErrors();
        }

        // find user from db
        $user = UserModel::find($id);

        $data = [
            'user' => $user,
            'success' => $success,
            'errors' => $errors,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }

    public function addUser($username): JsonResponse
    {
        $user = new UserModel();
        $user->username = $username;
        $user->password = password_hash($username, PASSWORD_DEFAULT);
        $user->email = $username . '@mail.com';
        $success = $user->save();
        $errors = $user->getValidationErrors();

        $data = [
            'user' => $user,
            'success' => $success,
            'errors' => $errors,
            'timestamp' => time(),
        ];

        return JsonResponse::make($data, statusCode: 200);
    }
}