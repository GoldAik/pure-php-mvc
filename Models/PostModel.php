<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/_Base/DatabaseModel.php';

use App\Models\_Base\DatabaseModel;
use App\Models\UserModel;

class PostModel extends DatabaseModel {
    protected static $table = 'Posts';

    public $id;
    public $content;
    public $user_id;

    public function user()
    {
        return UserModel::find($this->user_id);
    }
}