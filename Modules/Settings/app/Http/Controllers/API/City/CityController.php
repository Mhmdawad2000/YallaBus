<?php

namespace Modules\Settings\Http\Controllers\API\City;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Settings\Services\City\CityInterface;
use Modules\Settings\Http\Requests\City\CityRequest;
use Modules\Settings\Transformers\City\CityResource;
use Modules\Settings\Transformers\City\CityCollection;

class CityController extends Controller
{
    protected CityInterface $service;

    public function __construct(CityInterface $service)
    {
        $this->service = $service;
        $this->middleware(CheckPermission::class . ':create_city', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':update_city', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_city', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        [$status, $data] = $this->service->index($request);
        return $status
            ? $this->successResponse(new CityCollection($data), 200, 'تم جلب المدن بنجاح.')
            : $this->errorResponse($data, 500);
    }

    public function store(CityRequest $request)
    {
        [$status, $data] = $this->service->store($request->validated());
        return $status
            ? $this->successResponse(new CityResource($data), 201, 'تم إنشاء المدينة بنجاح.')
            : $this->errorResponse($data, 500);
    }

    public function show($id)
    {
        [$status, $data] = $this->service->show($id);
        return $status
            ? $this->successResponse(new CityResource($data), 200, 'تم جلب بيانات المدينة بنجاح.')
            : $this->errorResponse($data, 404);
    }

    public function update(CityRequest $request, $id)
    {
        [$status, $data] = $this->service->update($id, $request->validated());
        return $status
            ? $this->successResponse(new CityResource($data), 201, 'تم تحديث المدينة بنجاح.')
            : $this->errorResponse($data, 500);
    }

    public function destroy($id)
    {
        [$status, $data] = $this->service->delete($id);
        return match ($status) {
            true => $this->successResponse(null, 201, 'تم حذف المدينة بنجاح.'),
            2 => $this->errorResponse([], 400, $data),
            default => $this->errorResponse($data, 500, 'فشل في أنهاء الطلب')
        };
    }
}
