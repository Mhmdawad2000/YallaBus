<?php

namespace Modules\User\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\User\Models\EmailVerificationCode;

class PasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse(null, 400, 'This email does not exist.');
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        $data = [
            'name' => $user->full_name,
            'link' => url('/reset-password/' . $token . '?email=' . urlencode($request->email)),
        ];
        Mail::to($request->email)->queue(new SendMail($data, 'Reset Password', 'emails.reset-password'));

        return $this->successResponse(null, 201, 'We have emailed your password reset link!');
    }
    public function sendVerifyLinkEmail(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->errorResponse(null, 400, 'This email does not exist.');
        }
        $code = rand(100000, 999999);
        $email_verify = EmailVerificationCode::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        $data = [
            'name' => $user->full_name,
            'code' => $code,
            'link' => url("/auth/verify?email={$email_verify->email}&code={$email_verify->code}"),
        ];

        Mail::to($user->email)->queue(new SendMail($data, 'Verifietion email from yallabus', 'emails.email_verifietion'));
        return $this->successResponse(null, 201, 'We have emailed your email verify link!');
    }
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ],
        ]);

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return back()->withErrors(['email' => 'Invalid token.']);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(10)->isPast()) {
            return back()->withErrors(['email' => 'Token expired.']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->password = $request->password;
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->back()->with('status', 'Password has been reset!');
    }
}
