<?php

namespace Modules\Settings\Http\Controllers\API\Currency;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Settings\Services\Currency\CurrencyInterface;
use Modules\Settings\Http\Requests\Currency\CurrencyRequest;
use Modules\Settings\Transformers\Currency\CurrencyResource;
use Modules\Settings\Transformers\Currency\CurrencyCollection;

class CurrencyController extends Controller
{
    protected CurrencyInterface $service;

    public function __construct(CurrencyInterface $service)
    {
        $this->service = $service;
        $this->middleware(CheckPermission::class . ':toggle_default_currency', ['only' => ['toggleDefault']]);
        $this->middleware(CheckPermission::class . ':read_default_currency', ['only' => ['default']]);
        $this->middleware(CheckPermission::class . ':create_currency', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':update_currency', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_currency', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        [$status, $data] = $this->service->index($request);
        return $status
            ? $this->successResponse(new CurrencyCollection($data), 200, 'تم جلب العملات بنجاح.')
            : $this->errorResponse($data, 500, 'فشل في أنهاء الطلب');
    }

    public function store(CurrencyRequest $request)
    {
        [$status, $data] = $this->service->store($request->validated());
        return $status
            ? $this->successResponse(new CurrencyResource($data), 201, 'تم إنشاء العملة بنجاح.')
            : $this->errorResponse($data, 500, 'فشل في أنهاء الطلب');
    }

    public function show($id)
    {
        [$status, $data] = $this->service->show($id);
        return $status
            ? $this->successResponse(new CurrencyResource($data), 200, 'تم جلب بيانات العملة بنجاح.')
            : $this->errorResponse($data, 404, 'فشل في أنهاء الطلب');
    }

    public function default()
    {
        [$status, $data] = $this->service->default();
        return $status
            ? $this->successResponse(new CurrencyResource($data), 200, 'تم جلب بيانات العملة بنجاح.')
            : $this->errorResponse([], 404, $data);
    }
    public function update(CurrencyRequest $request, $id)
    {
        [$status, $data] = $this->service->update($id, $request->validated());
        return $status
            ? $this->successResponse(new CurrencyResource($data), 201, 'تم تحديث العملة بنجاح.')
            : $this->errorResponse($data, 500, 'فشل في أنهاء الطلب');
    }

    public function destroy($id)
    {
        [$status, $data] = $this->service->delete($id);
        return match ($status) {
            true => $this->successResponse(null, 201, 'تم حذف العملة بنجاح.'),
            2 => $this->errorResponse([], 400, $data),
            default => $this->errorResponse($data, 500, 'فشل في أنهاء الطلب')
        };
    }

    public function toggleDefault($id)
    {
        [$status, $data] = $this->service->toggleDefault($id);
        return $status
            ? $this->successResponse([], 201, 'تم تغير العملة الإفتراضية بنجاح.')
            : $this->errorResponse([], 400, $data);
    }
}
