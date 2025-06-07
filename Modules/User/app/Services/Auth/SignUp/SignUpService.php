<?php

namespace Modules\User\Services\Auth\SignUp;

use Exception;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\User\Models\EmailVerificationCode;
use Modules\User\Services\Auth\Repository\LoginRepository;
use Modules\User\Services\Auth\Repository\SignUpRepository;

class SignUpService implements SignUpInterface
{
    private SignUpRepository $signUpRepository;
    private LoginRepository $loginRepository;
    public function __construct(SignUpRepository $signUpRepository, LoginRepository $loginRepository)
    {
        $this->signUpRepository = $signUpRepository;
        $this->loginRepository = $loginRepository;
    }
    public function signUpUser(array $data): array
    {

        try {
            $data['role_id'] = 4;
            $user = $this->signUpRepository->addUser($data);
            $login = $this->loginRepository->login($user);
            if ($login['status']) {
                return [true, ['user' => $login['user'], 'token' => $login['token']], 201, 'تم إنشاء الحساب'];
            }
            return [false, null, 400, $login['message']];
        } catch (Exception $e) {
            Log::error('SignUpService signUpUser :', [$e->getMessage()]);
            return [false, null, 400, 'حدث خطأ معين عند إنشاء الحساب'];
        }
    }

    public function verify(Request $request): array
    {
        try {
            if (!$request->filled('email') || !$request->filled('code')) {
            }
            $emailVerify = EmailVerificationCode::where('email', $request->email)
                ->where('code', $request->code)
                ->first();
            if (!$emailVerify || $emailVerify->expires_at < now()) {
                return [false, null, 400, 'كود التحقق أو البريد الإلكتروني غير صحيح أو منتهي الصلاحية.'];
            }
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return [false, null, 404, 'المستخدم غير موجود.'];
            }
            $user->update(['email_verified_at' => now()]);
            $emailVerify->delete();
            return [true, null, 200, 'تم التحقق من البريد الإلكتروني بنجاح.'];
        } catch (Exception $e) {
            Log::error('SignUpService verify :', ['message' => $e->getMessage()]);

            return [false, null, 500, 'حدث خطأ أثناء التحقق من البريد الإلكتروني.'];
        }
    }

}
