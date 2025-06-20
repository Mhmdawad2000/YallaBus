<?php

namespace Modules\User\Http\Controllers\UserCRUD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\User\Transformers\User\UserResource;
use Modules\User\Services\UserCRUD\UserCRUDInterface;
use Modules\User\Http\Requests\UserCRUD\AvatarRequest;
use Modules\User\Http\Requests\UserCRUD\UpdateProfileRequest;
use Modules\User\Http\Requests\UserCRUD\ChangePasswordRequest;
use Modules\User\Http\Requests\UserCRUD\UpdateContactInfoRequest;

class UserCRUDController extends Controller
{
    private UserCRUDInterface $userCRUDInterface;
    public function __construct(UserCRUDInterface $userCRUDInterface)
    {
        $this->userCRUDInterface = $userCRUDInterface;
        // $this->middleware(CheckPermission::class . ':change_password', ['only' => ['changePassword']]);
        // $this->middleware(CheckPermission::class . ':update_profile', ['only' => ['updateProfile']]);
        // $this->middleware(CheckPermission::class . ':update_contact_info', ['only' => ['updateContactInfo']]);
        // $this->middleware(CheckPermission::class . ':upload_avatar', ['only' => ['avatar']]);
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        $request->validated();
        [$status, $code, $message] = $this->userCRUDInterface->changePassword($request);
        return match ($status) {
            2 => $this->errorResponse([], $code ?? 400, $message),
            1 => $this->successResponse(null, $code ?? 201, $message),
            default => $this->errorResponse([], $code ?? 400, $message),
        };
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        $request->validated();
        [$status, $code, $message] = $this->userCRUDInterface->updateProfile($request);
        return match ($status) {
            2 => $this->errorResponse([], $code ?? 400, $message),
            1 => $this->successResponse(null, $code ?? 201, $message),
            default => $this->errorResponse([], $code ?? 400, $message),
        };
    }

    public function updateContactInfo(UpdateContactInfoRequest $request)
    {
        $request->validated();
        [$status, $code, $logout, $message] = $this->userCRUDInterface->updateContactInfo($request);
        return match ($status) {
            2 => $this->errorResponse(['logout' => $logout], $code ?? 400, $message),
            1 => $this->successResponse(['logout' => $logout], $code ?? 201, $message),
            default => $this->errorResponse(['logout' => $logout], $code ?? 400, $message),
        };
    }
    public function avatar(AvatarRequest $request)
    {
        $request->validated();
        [$status, $data, $message] = $this->userCRUDInterface->avatar($request);
        return match ($status) {
            2 => $this->errorResponse([], $data ?? 400, $message),
            1 => $this->successResponse(UserResource::make($data), 201, $message),
            default => $this->errorResponse([], $data ?? 400, $message),
        };
    }

}
