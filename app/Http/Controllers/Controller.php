<?php

namespace App\Http\Controllers;

abstract class Controller
{
    private function response($status, $message, $data = [], $code = 200)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
