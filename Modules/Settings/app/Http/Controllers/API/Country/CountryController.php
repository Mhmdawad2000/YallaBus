<?php


namespace Modules\Settings\Http\Controllers\API\Country;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Settings\Services\Country\CountryInterface;
use Modules\Settings\Http\Requests\Country\CountryRequest;
use Modules\Settings\Transformers\Country\CountryResource;
use Modules\Settings\Transformers\Country\CountryCollection;

class CountryController extends Controller
{
    protected CountryInterface $service;

    public function __construct(CountryInterface $service)
    {
        $this->service = $service;
         $this->middleware(CheckPermission::class . ':create_country', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':update_country', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_country', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        [$status, $data] = $this->service->index($request);
        return $status
            ? $this->successResponse(new CountryCollection($data), 200, 'تم جلب الدول بنجاح.')
            : $this->errorResponse([], 400, $data);
    }

    public function store(CountryRequest $request)
    {
        [$status, $data] = $this->service->store($request->validated());
        return $status
            ? $this->successResponse(new CountryResource($data), 201, 'تم إنشاء الدولة بنجاح.')
            : $this->errorResponse([], 400, $data);
    }

    public function show($id)
    {
        [$status, $data] = $this->service->show($id);
        return $status
            ? $this->successResponse(new CountryResource($data), 200, 'تم جلب الدولة بنجاح.')
            : $this->errorResponse([], 404, $data);
    }

    public function update(CountryRequest $request, $id)
    {
        [$status, $data] = $this->service->update($id, $request->validated());
        return $status
            ? $this->successResponse(new CountryResource($data), 201, 'تم تحديث الدولة بنجاح.')
            : $this->errorResponse([], 400, $data);
    }

    public function destroy($id)
    {
        [$status, $data] = $this->service->delete($id);
        return match ($status) {
            true => $this->successResponse(null, 201, 'تم حذف الدولة بنجاح.'),
            2 => $this->errorResponse([], 400, $data),
            default => $this->errorResponse($data, 500, 'فشل في أنهاء الطلب')
        };
    }
}
