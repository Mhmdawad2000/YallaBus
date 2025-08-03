<?php

namespace Modules\Company\Http\Controllers\Bus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Company\Services\Bus\BusInterface;
use Modules\Company\Http\Requests\Bus\BusRequest;
use Modules\Company\Transformers\Bus\BusResource;
use Modules\Company\Transformers\Bus\BusCollection;

class BusController extends Controller
{
    private BusInterface $busInterface;

    public function __construct(BusInterface $busInterface)
    {
        $this->busInterface = $busInterface;
         $this->middleware(CheckPermission::class . ':create_bus', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':read_all_buses', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_bus', ['only' => ['show']]);
        $this->middleware(CheckPermission::class . ':update_bus', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_bus', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->busInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BusCollection($data),
            $code,
            $message
        );
    }

    public function store(BusRequest $request)
    {
        [$status, $data, $code, $message] = $this->busInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BusResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->busInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BusResource($data),
            $code,
            $message
        );
    }

    public function update(BusRequest $request, $id)
    {
        [$status, $data, $code, $message] = $this->busInterface->update($request, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BusResource($data),
            $code,
            $message
        );
    }

    public function destroy($id)
    {
        [$status, $data, $code, $message] = $this->busInterface->destroy($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }
}