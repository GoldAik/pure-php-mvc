<?php

declare(strict_types = 1);

namespace App\Controlls;

use App\View;

class Home{
    public function home(): View
    {
        return View::make('home');
    }

    public function homeWithParams(): View
    {
        $params = array( 'data' => array(
            'sendTime' => time(),
            'message' => 'Message from func homeWithParams',
        ));
        return View::make('home', $params);
    }
}