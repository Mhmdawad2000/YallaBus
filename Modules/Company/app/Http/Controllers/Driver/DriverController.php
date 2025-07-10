<?php

namespace Modules\Company\Http\Controllers\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Company\Services\Driver\DriverInterface;
use Modules\Company\Http\Requests\Driver\DriverRequest;
use Modules\Company\Transformers\Driver\DriverResource;
use Modules\Company\Transformers\Driver\DriverCollection;

class DriverController extends Controller
{
    private DriverInterface $driverInterface;

    public function __construct(DriverInterface $driverInterface)
    {
        $this->driverInterface = $driverInterface;
        $this->middleware(CheckPermission::class . ':create_driver', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':read_all_drivers', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_driver', ['only' => ['show']]);
        $this->middleware(CheckPermission::class . ':update_driver', ['only' => ['update']]);
        $this->middleware(CheckPermission::class . ':delete_driver', ['only' => ['destroy']]);
    }

    public function index(string $role, Request $request)
    {
        [$status, $data, $code, $message] = $this->driverInterface->index($role, $request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new DriverCollection($data),
            $code,
            $message
        );
    }

    public function store(string $role, DriverRequest $request)
    {
        [$status, $data, $code, $message] = $this->driverInterface->store($role, $request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new DriverResource($data),
            $code,
            $message
        );
    }

    public function show(string $role, $id)
    {
        [$status, $data, $code, $message] = $this->driverInterface->show($role, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new DriverResource($data),
            $code,
            $message
        );
    }

    public function update(string $role, DriverRequest $request, $id)
    {
        [$status, $data, $code, $message] = $this->driverInterface->update($role, $request, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new DriverResource($data),
            $code,
            $message
        );
    }

    public function destroy(string $role, $id)
    {
        [$status, $data, $code, $message] = $this->driverInterface->destroy($role, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }
}
