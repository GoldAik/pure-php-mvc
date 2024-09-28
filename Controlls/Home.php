<?php

declare(strict_types = 1);

namespace App\Controlls;

require_once \APP_PATH . '/Http/JsonResponse.php';
require_once \APP_PATH . '/Models/User.php';
require_once \APP_PATH . '/Models/UserModel.php';

use App\Controlls\_Base\Controlls;

use App\Http\Response;
use App\Http\JsonResponse;
use App\Http\View;

use App\Models\User;
use App\Models\UserModel;

class Home extends Controlls{
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

    public function getRequest(): View
    {
        // if null return ''
        $message = $this->request->get('m');

        $params = array( 'data' => array(
            'sendTime' => time(),
            'message' => "Message from get method: $message",
        ));
        return View::make('home', $params);
    }

    public function getRequestSendResponse(): Response
    {
        // if null return ''
        $message = $this->request->get('m');
        $time = time();

        return Response::html("message: $message <br> timestamp: $time");
    }

    public function getRequestSendJsonResponse(): JsonResponse
    {
        $message = $this->request->get('m');
        $time = time();

        $data = [
            'message' => $message,
            'timestamp' => $time,
        ];

        return JsonResponse::make(200, $data);
    }

    public function getUser($userId): JsonResponse
    {
        $data = [
            'message' => $userId,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function getBook($shelfId, $bookId): JsonResponse
    {
        $data = [
            'shelfId' => $shelfId,
            'bookId' => $bookId,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function getNegativeNumber($num): JsonResponse
    {
        $data = [
            'num' => $num,
            'timestamp' => time(),
        ];

        return JsonResponse::make(200, $data);
    }

    public function noView(): View
    {
        return View::make('no-view');
    }
}