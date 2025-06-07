<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\User\Services\Auth\Login\LoginInterface;

class LoginController extends Controller
{
    private LoginInterface $loginService;
    public function __construct(LoginInterface $loginService)
    {
        $this->loginService = $loginService;
    }
    public function login(Request $request)
    {
        [$status, $data, $code, $message] = $this->loginService->login($request);
        return $status ?
            $this->successResponse($data, $code, $message) : $this->errorResponse($data, $code, $message);
    }

}
