<?php

namespace Modules\User\Services\Auth\Repository;

use App\Mail\SendMail;
use Carbon\Carbon;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use function Laravel\Prompts\password;
use Modules\User\Models\EmailVerificationCode;

class SignUpRepository
{
    public function addUser(array $data)
    {
        $user = User::create($data);
        Log::info("SignUpRepository addUser :{$user->full_name} Register successfully");
        $user->assignRole('user');
        $code = rand(100000, 999999);
        $email_verify = EmailVerificationCode::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        $data = [
            'name' => $user->first_name,
            'code' => $code,
            'link' => url("/auth/verify?email={$email_verify->email}&code={$email_verify->code}"),
        ];

        Mail::to($user->email)->queue(new SendMail($data, 'Verifietion email from yallabus', 'emails.email_verifietion'));


        return $user;
    }
}
