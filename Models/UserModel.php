<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/DatabaseModel.php';
require_once \APP_PATH . '/Models/PostModel.php';

use App\Models\DatabaseModel;
use App\Models\PostModel;

class UserModel extends DatabaseModel {
    protected static $table = 'Users';

    public $id;
    public $username;
    public $password;
    public $email;

    public function posts()
    {
        return PostModel::where('user_id', $this->id);
    }
}