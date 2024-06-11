<?php

declare(strict_types = 1);

namespace App\Http;

use App\App;

class View{

    public function __construct(protected string $view, protected array $params = [])
    {

    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {   
        $data = $this->params['data'] ?? null;
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';
        
        $view = $this->ob_load_view($viewPath, $data);

        if(!$view) return '404';

        return $view;
    }

    private function ob_load_view($viewPath, $data = null): ?string
    {
        if(!file_exists($viewPath)){
            return null;
        }

        ob_start();
        include $viewPath;
        return (string) ob_get_clean();
    }

    public function __toString(): string
    {
        return $this->render();
    }
}