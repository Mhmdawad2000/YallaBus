<?php

namespace Modules\User\Services\UserCRUD;

use Exception;
use Carbon\Carbon;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\User\Models\EmailVerificationCode;

class UserCRUDService implements UserCRUDInterface
{
    public function changePassword(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if (!$user) {
                return [2, 404, 'فشل في إيجاد المستخدم'];
            }

            if (!Hash::check($request->old_password, $user->password)) {
                return [2, 400, 'كلمة المرور القديمة غير صحيحة'];
            }
            $user->update(['password' => $request->new_password]);
            return [1, 201, 'تم تغيير كلمة المرور بنجاح'];
        } catch (Exception $e) {
            Log::error("UserCRUDService changePassword : ", [$e->getMessage()]);
            return [0, 500, 'حدث خطأ أثناء تغيير كلمة المرور'];
        }
    }


    public function updateProfile(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if (!$user) {
                return [2, 404, 'فشل في إيجاد المستخدم'];
            }
            if ($user->hasRole('super-admin')) {
                return [2, 400, 'المشرف الأعلى لا يمكن تعديله'];
            }
            $data = $request->only([
                'first_name',
                'last_name',
                'city_id'
            ]);
            $user->update($data);
            return [1, 201, 'تم تحديث الملف الشخصي بنجاح'];
        } catch (Exception $e) {
            Log::error("UserCRUDService updateProfile : ", [$e->getMessage()]);
            return [0, 500, 'حدث خطأ أثناء تحديث الملف الشخصي'];
        }
    }
    public function updateContactInfo(Request $request)
    {
        try {
            $user = User::find(Auth::id());
            if (!$user) {
                return [2, 404, false, 'فشل في إيجاد المستخدم'];
            }
            if ($user->hasRole('super-admin')) {
                return [2, 400, false, 'المشرف الأعلى لا يمكن تعديله'];
            }
            $data = $request->only([
                'phone',
                'email',
            ]);
            $logout = false;
            if ($user->email !== $request->email) {
                $data['email_verified_at'] = null;
                $logout = true;
                $code = rand(100000, 999999);
                $email_verify = EmailVerificationCode::create([
                    'email' => $request->email,
                    'code' => $code,
                    'expires_at' => Carbon::now()->addMinutes(10),
                ]);
                $dataSend = [
                    'name' => $user->full_name,
                    'code' => $code,
                    'link' => url("/auth/verify?email={$email_verify->email}&code={$email_verify->code}"),
                ];
                Mail::to($request->email)->queue(new SendMail($dataSend, 'Verifietion email from yallabus', 'emails.email_verifietion'));
            }
            $user->update($data);
            return [1, 201, $logout, 'تم تحديث الملف الشخصي بنجاح'];
        } catch (Exception $e) {
            Log::error("UserCRUDService updateProfile : ", [$e->getMessage()]);
            return [0, 500, false, 'حدث خطأ أثناء تحديث الملف الشخصي'];
        }
    }

    public function avatar(Request $request)
    {
        try {
            $user = User::find(Auth::id());

            if (!$user) {
                return [2, 404, 'فشل في إيجاد المستخدم'];
            }
            if ($user->hasRole('super-admin')) {
                return [2, 400, 'المشرف الأعلى لا يمكن تعديله'];
            }
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = null;
            if ($request->file('avatar')) {
                $file = $request->file('avatar');
                $path = $file->store("avatars/userID-{$user->id}", 'public');
            }
            $user->update(['avatar' => $path]);
            $user->load(['city', 'role', 'role.permissions']);
            return [1, $user, 'تم تحديث الصورة الشخصية بنجاح'];
        } catch (Exception $e) {
            Log::error("UserCRUDService avatar : ", [$e->getMessage()]);
            return [0, 500, 'حدث خطأ أثناء تحديث الصورة الشخصية'];
        }
    }
}
