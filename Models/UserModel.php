<?php

declare(strict_types = 1);

namespace App\Models;

require_once \APP_PATH . '/Models/_Base/DatabaseModel.php';
require_once \APP_PATH . '/Models/PostModel.php';

require_once \APP_PATH . '/Models/_Base/Validator.php';

use App\Models\_Base\DatabaseModel;
use App\Models\PostModel;

use App\Models\_Base\Validator;

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

    private $validationRules = [
        'username' => [
            'required' => true,
            'minLength' => 3,
            'maxLength' => 30,
        ],
        'password' => [
            'required' => true,
            'minLength' => 8,
        ],
        'email' => [
            'required' => true,
            'email' => true,
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->validator->setRules($this->validationRules);
    }

    protected function validateUsername(): bool
    {
        $valid = !$this->isUsernameTaken($this->username);
        if(!$valid) $this->validator->addError('username', 'Username is taken');
        return $valid;
    }

    protected function validatePassword(): bool
    {
        return true;
    }

    protected function validateEmail(): bool
    {
        return true;
    }

    private function isUsernameTaken($username): bool
    {
        $user = UserModel::where('username', $username);
        return !empty($user);
    }
}