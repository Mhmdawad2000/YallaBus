<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
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
