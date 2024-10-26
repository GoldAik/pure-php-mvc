<?php

declare(strict_types = 1);

namespace App\Controllers\_Base;

class Controller {
    public function __construct(protected \App\Http\Request $request) {

    }
}