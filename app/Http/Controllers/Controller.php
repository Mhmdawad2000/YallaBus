<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponse($data, $code = 200, $message = '')
    {
        return response()->json([
            'data' => $data,
            'status' => 'success',
            'code' => $code,
            'message' => $message
        ], $code);
    }

    public function errorResponse($data, $code = 400, $message = '')
    {
        return response()->json([
            'data' => $data,
            'status' => 'error',
            'code' => $code,
            'message' => $message
        ], $code);
    }
}
