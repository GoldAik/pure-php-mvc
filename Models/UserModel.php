<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/DatabaseModel.php';

use App\Models\DatabaseModel;

class UserModel extends DatabaseModel {
    protected static $table = 'Users';

    public $id;
    public $username;
    public $password;
    public $email;
}