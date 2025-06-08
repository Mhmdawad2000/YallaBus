<?php

namespace Modules\User\Services\Auth\Login;
use Exception;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Modules\User\Services\Auth\Repository\LoginRepository;

class LoginService implements LoginInterface
{
    private LoginRepository $loginRepository;
    public function __construct(LoginRepository $loginRepository)
    {
        $this->loginRepository = $loginRepository;
    }
    public function login(Request $request): array
    {
        $request->validate([
            'email_or_phone' => 'required',
            'password' => 'required',
        ]);
        try {
            $user = User::where('email', $request->email_or_phone)
                ->orWhere('phone', $request->email_or_phone)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return [false, null, 404, 'بيانات تسجيل الدخول غير صحيحة'];
            }
            $login = $this->loginRepository->login($user);
            if ($login['status']) {
                return [true, ['user' => $login['user'], 'token' => $login['token']], 201, 'تم تسجيل الدخول بنجاح'];
            }
            return [false, null, 400, $login['message']];
        } catch (Exception $e) {
            Log::error('LoginService login :', ['message' => $e->getMessage()]);
            return [false, null, 500, 'حدث خطأ أثناء محاولة تسجيل الدخول'];
        }
    }

}
