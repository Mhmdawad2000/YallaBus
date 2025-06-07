<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Modules\User\Http\Requests\RegisterRequest;
use Modules\User\Services\Auth\SignUp\SignUpInterface;

class SignUpController extends Controller
{
    private SignUpInterface $signUpService;
    public function __construct(SignUpInterface $signUpService)
    {
        $this->signUpService = $signUpService;
    }
    public function signUp(RegisterRequest $request)
    {
        [$status, $data, $code, $message] = $this->signUpService->signUpUser($request->validated());
        return $status ?
            $this->successResponse($data, $code, $message) : $this->errorResponse($data, $code, $message);
    }

    public function verify(Request $request): JsonResponse|View
    {


        [$status, $data, $code, $message] = $this->signUpService->verify($request);
        if (!$request->expectsJson()) {
            return view('auth.verify',['message' => $message]);
        }
        return $status ?
            $this->successResponse($data, $code, $message) : $this->errorResponse($data, $code, $message);
    }
}
