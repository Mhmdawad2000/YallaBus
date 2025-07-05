<?php

namespace Modules\User\Http\Controllers\Mangement_Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\User\Transformers\User\UserResource;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserManagement\IUserManagementService;

class UserMangementController extends Controller
{
  protected IUserManagementService $userManagementService;

  /**
   * حقن الخدمة وتفعيل الميدلوير للتحكم بالصلاحيات.
   */
  public function __construct(IUserManagementService $userManagementService)
  {
    $this->userManagementService = $userManagementService;

    $this->middleware(CheckPermission::class . ':create_user', ['only' => ['store']]);
    $this->middleware(CheckPermission::class . ':read_user', ['only' => ['show']]);
    $this->middleware(CheckPermission::class . ':read_all_users', ['only' => ['index']]);
    $this->middleware(CheckPermission::class . ':update_user', ['only' => ['update']]);
    $this->middleware(CheckPermission::class . ':delete_user', ['only' => ['delete']]);
  }

  /**
   * إضافة مستخدم جديد.
   */
  public function store(CreateUserRequest $request)
  {
    try {
      $user = $this->userManagementService->store($request);
      if ($user) {
        return $this->successResponse(UserResource::make($user), 201, 'تم إضافة المستخدم بنجاح');
      }
      return $this->errorResponse([], 400, 'فشل في إضافة المستخدم');
    } catch (\Exception $e) {
    Log::info('story user management',[$e]);
          return $this->errorResponse([], 500, 'حدث خطأ أثناء إضافة المستخدم');
    }
  }

  /**
   * جلب بيانات مستخدم واحد بواسطة المعرف.
   */
  public function show($userId)
  {
    $user = $this->userManagementService->show($userId);

    if (!$user) {
      return $this->errorResponse([], 404, 'المستخدم غير موجود');
    }
    return $this->successResponse(UserResource::make($user), 200, 'تم جلب بيانات المستخدم بنجاح');
  }

  /**
   * جلب قائمة المستخدمين (مع إمكانية التصفية والبحث).
   */
  public function index(Request $request)
  {
    $users = $this->userManagementService->index($request);

    if ($users && $users->count() > 0) {
      return $this->successResponse(UserResource::collection($users), 200, 'تم جلب المستخدمين بنجاح');
    }

    return $this->errorResponse([], 200, 'لا يوجد مستخدمين لعرضهم');
  }

  /**
   * تحديث بيانات مستخدم موجود.
   */
  public function update($userId, UpdateUserRequest $request)
  {
    try {
      $result = $this->userManagementService->update($userId, $request);

      return match ($result['status']) {
        404 => $this->errorResponse([], 404, $result['message'] ?: 'المستخدم غير موجود'),
        401 => $this->errorResponse([], 401, $result['message'] ?: 'غير مصرح بالتحديث'),
        201 => $this->successResponse(UserResource::make($result['data']), 201, $result['message'] ?: 'تم تحديث بيانات المستخدم بنجاح'),
        default => $this->errorResponse([], 400, $result['message'] ?: 'فشل في تحديث بيانات المستخدم'),
      };
    } catch (\Exception $e) {
      return $this->errorResponse([], 500, 'حدث خطأ أثناء تحديث بيانات المستخدم');
    }
  }

  /**
   * حذف مستخدم.
   */
  public function delete($id)
  {
    try {
      [$status, $code, $message] = $this->userManagementService->delete($id);

      return match ($status) {
        2 => $this->errorResponse([], $code, $message),
        1 => $this->successResponse(null, 201, 'تم حذف المستخدم بنجاح'),
        default => $this->errorResponse([], 400, 'فشل في حذف المستخدم'),
      };
    } catch (\Exception $e) {
      return $this->errorResponse([], 500, 'حدث خطأ أثناء حذف المستخدم');
    }
  }
}
