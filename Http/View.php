<?php

declare(strict_types = 1);

namespace App\Http;

use App\Http\Response;
use App\App;

class View extends Response{

    public function __construct(protected string $view, protected array $params = [], private $statusCode = 200, private $header = self::DEFAULT_HEADER)
    {
        parent::__construct(statusCode: 200, header: Response::DEFAULT_HEADER, body: '');
    }

    public static function make(string $view, array $params = []): static
    {
        return new static(view: $view, params: $params);
    }

    public function render(): string
    {   
        $data = $this->params['data'] ?? null;
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        $view = $this->ob_load_view($viewPath, $data);

        if(!$view){
            parent::setStatusCode(404);
            return '404 - no view';
        }

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
        $body = $this->render();
        parent::setBody($body);
        return parent::__toString();
    }
}