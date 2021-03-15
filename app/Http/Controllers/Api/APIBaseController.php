<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class APIBaseController extends Controller
{
    public function sendResponse($result, $message = 'success')
    {
        $response = [
            "success" => true,
            "data" => $result,
            "message" => $message,
        ];
        return response()->json($response, 200);
    }
    public function sendAuthSuccess($payload, $user, $message)
    {
        $response = [
            "success" => true,
            "user" => $user,
            "payload" => $payload,
            "message" => $message,
        ];
        return response()->json($response, 200);
    }
    public function sendError($error, $code = 404)
    {
        $response = [
            "success" => false,
            "message" => $error,
        ];
        return response()->json($response, $code);
    }
}