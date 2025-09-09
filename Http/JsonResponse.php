<?php

declare(strict_types = 1);

namespace App\Http;

use App\Http\Response;

class JsonResponse extends Response
{
    public static function make(array $data, int $statusCode = 200): static
    {
        $headers = [
            'Content-Type' => 'application/json',
        ];
        
        $json = json_encode($data);

        return new static(statusCode: $statusCode, headers: $headers, body: $json);
    }
}