<?php

namespace Modules\User\Services\UserManagement;


use Illuminate\Http\Request;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Log;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Services\UserManagement\IUserManagementService;
use Spatie\Permission\Models\Role;

class UserManagementService implements IUserManagementService
{

  public function store(CreateUserRequest $request): User
  {
    $data = $request->validated();
    $data['email_verified_at'] = now();
    $user = User::create($data);
    $role = Role::findOrFail($request->role_id);
    $user->assignRole($role->name);

    return $user;
  }

  public function show($userId)
  {
    return User::where('id', $userId)->with(['city', 'role', 'permissions'])->first();
  }

  public function index(Request $request)
  {
    $perPage = $request->per_page ? $request->per_page : 10;
    return User::filter($request)->with(['city', 'role', 'permissions'])->paginate($perPage);
  }
  public function update($id, UpdateUserRequest $request)
  {
    $data =$request->validated();
    $user = User::find($id);
    if (!$user) {
      return [
        'success' => false,
        'message' => 'المستخدم غير موجود',
        'status' => 404
      ];
    }

    if ($user->hasRole('super-admin')) {
      return [
        'success' => false,
        'message' => 'المشرف الأعلى لا يمكن تعديله',
        'status' => 400
      ];
    }

    if ($request->filled('role_id')) {
      $role = Role::findOrFail($request->role_id);
      $user->syncRoles([$role->name]);
    }

    $user->update($data);

    $user->load(['role', 'permissions', 'city']);
    return [
      'data' => $user,
      'success' => true,
      'message' => 'تم تحديث الملف الشخصي',
      'status' => 201
    ];
  }
  public function delete(int $user_id): array
  {
    try {
      $user = User::find($user_id);
      if (!$user) {
        return [3, []];

      }
      if ($user->hasRole('super-admin')) {
        return [2, 400, 'المشرف الأعلى لا يمكن حذفه'];
      }

      // if ($user->Courses()->exists()) {
      //   Log::info("User => [ID: {$user->id}] Cannot delete: user courses exist.");
      //   return [4, []]; // User courses exist
      // }
      $user->save();
      $user->delete();

      Log::info("User soft deleted successfully => [ID: {$user->id}]");
      return [1, null, ''];
    } catch (\Exception $e) {
      Log::error("Failed to soft delete user: " . $e->getMessage());
      return [false, [], ''];
    }
  }
}
