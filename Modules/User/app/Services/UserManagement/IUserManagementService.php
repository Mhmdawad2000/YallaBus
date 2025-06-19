<?php

namespace Modules\User\Services\UserManagement;


use Illuminate\Http\Request;
use Modules\User\Models\User;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;

interface IUserManagementService
{
  public function store(CreateUserRequest $user): User;
  public function show($userId);
  public function index(Request $request);
  public function update($id, UpdateUserRequest $request);
  public function delete(int $user_id): array;
}
