<?php

namespace Modules\Settings\Http\Controllers\API\Permission;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Symfony\Component\HttpFoundation\Response;
use Modules\Settings\Services\Permission\IPermissionService;
use Modules\Settings\Http\Requests\Permission\PermissionRequest;
use Modules\Settings\Transformers\Permission\PermissionResource;
use Modules\Settings\Transformers\Permission\PermissionCollection;

/**
 * @group إدارة الصلاحيات (Permissions)
 *
 * نقاط النهاية (Endpoints) لإدارة الصلاحيات
 */
class PermissionController extends Controller
{
    private IPermissionService $Permission_service;

    public function __construct(IPermissionService $Permission_service)
    {
        $this->Permission_service = $Permission_service;
        // $this->middleware(CheckPermission::class . ':read_all_permissions', ['only' => ['index']]);
        // $this->middleware(CheckPermission::class . ':read_permission', ['only' => ['show']]);
        // $this->middleware(CheckPermission::class . ':update_permission', ['only' => ['update']]);
    }

    /**
     * عرض جميع الصلاحيات.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $Permissions = $this->Permission_service->list();
            return $this->successResponse(PermissionResource::collection($Permissions), Response::HTTP_OK, 'تم جلب الصلاحيات بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse('فشل في جلب الصلاحيات.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * عرض صلاحية محددة.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $data = $this->Permission_service->get($id);
            if ($data[0] == 3) {
                return $this->errorResponse([], Response::HTTP_NOT_FOUND, "الصلاحية غير موجودة.");
            }
            return $this->successResponse(new PermissionResource($data[1]), Response::HTTP_OK, 'تم جلب الصلاحية بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    /**
     * تحديث صلاحية محددة.
     *
     * @param PermissionRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PermissionRequest $request, $id)
    {
        try {
            $data = $this->Permission_service->update($id, $request->validated());
            if ($data[0] == 3) {
                return $this->errorResponse([], Response::HTTP_NOT_FOUND, "الصلاحية غير موجودة.");
            }
            return $this->successResponse(new PermissionResource($data[1]), Response::HTTP_CREATED, 'تم تحديث الصلاحية بنجاح.');
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
