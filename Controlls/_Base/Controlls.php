<?php

declare(strict_types = 1);

namespace App\Controlls\_Base;

class Controlls {
    public function __construct(protected \App\Http\Request $request) {

    }
}