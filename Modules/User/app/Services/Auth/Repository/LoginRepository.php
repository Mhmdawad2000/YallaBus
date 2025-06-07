<?php

namespace Modules\User\Services\Auth\Repository;

use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\User\Transformers\User\UserResource;

class LoginRepository
{
    public function login(User $user): array
    {
        if ($user->email_verified_at) {

            Auth::login($user);
            // تحديث بيانات آخر تسجيل دخول
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => request()->ip(),
            ]);

            // إنشاء access token
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info("LoginRepository login :{$user->full_name} Login successfully");
            $user->load(['city', 'role', 'role.permissions']);
            return [
                'status' => true,
                'user' => UserResource::make($user),
                'token' => $token,
            ];
        } else {
            return [
                'status' => false,
                'message' => 'يجب التحقق من الايميل',
            ];
        }
    }
}
