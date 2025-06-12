<?php

namespace Modules\Settings\Http\Controllers\API\Role;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckPermission;
use Symfony\Component\HttpFoundation\Response;
use Modules\Settings\Services\Role\IRoleService;
use Modules\Settings\Transformers\Role\RoleResource;
use Modules\Settings\Transformers\Role\MyRoleResource;
use Modules\Settings\Transformers\Role\RoleCollection;
use Modules\Settings\Http\Requests\Role\AddRoleRequest;
use Modules\Settings\Http\Requests\Role\UpdateRoleRequest;

/**
 * @group Role Management
 *
 * API endpoints for managing roles and permissions
 */
class RoleController extends Controller
{
    private IRoleService $role_service;

    public function __construct(IRoleService $role_service)
    {
        $this->role_service = $role_service;

        $this->middleware(CheckPermission::class . ':read_all_roles', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_role', ['only' => ['show']]);
        $this->middleware(CheckPermission::class . ':create_role', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':update_role', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_role', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $roles = $this->role_service->list();
            return $this->successResponse(RoleResource::collection($roles), Response::HTTP_OK, 'تم جلب الأدوار بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse('فشل في جلب الأدوار.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(AddRoleRequest $request)
    {
        try {
            $role = $this->role_service->create($request->validated());
            return $this->successResponse(new RoleResource($role), Response::HTTP_CREATED, 'تم إنشاء الدور بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = $this->role_service->get($id);
            if ($data[0] == 3) {
                return $this->errorResponse([], Response::HTTP_NOT_FOUND, "الدور غير موجود.");
            }
            return $this->successResponse(new RoleResource($data[1]), Response::HTTP_OK, 'تم جلب الدور بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        try {
            $data = $this->role_service->update($id, $request->validated());
            if ($data[0] == 2) {
                return $this->errorResponse([], Response::HTTP_BAD_REQUEST, "لا يمكن تعديل دور 'Super Admin'.");
            } elseif ($data[0] == 3) {
                return $this->errorResponse([], Response::HTTP_NOT_FOUND, "الدور غير موجود.");
            }
            return $this->successResponse(new RoleResource($data[1]), Response::HTTP_CREATED, 'تم تحديث الدور بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = $this->role_service->delete($id);
            if ($data[0] == 2) {
                return $this->errorResponse([], Response::HTTP_BAD_REQUEST, $data[1]);
            } elseif ($data[0] == 3) {
                return $this->errorResponse([], Response::HTTP_NOT_FOUND, $data[1]);
            }
            return $this->successResponse([], Response::HTTP_CREATED, 'تم حذف الدور بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], Response::HTTP_BAD_REQUEST, 'فشل في حذف الدور.');
        }
    }

    // public function showMyRole()
    // {
    //     $id = Auth::user()->role_id;
    //     try {
    //         $data = $this->role_service->get($id);
    //         if ($data[0] == 3) {
    //             return $this->errorResponse([], Response::HTTP_NOT_FOUND, "الدور غير موجود.");
    //         }
    //         return $this->successResponse(new MyRoleResource($data[1]), Response::HTTP_OK, 'تم جلب دوري بنجاح.');
    //     } catch (Exception $e) {
    //         return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
    //     }
    // }
}
