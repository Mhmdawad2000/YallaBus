<?php

namespace Modules\Trip\Http\Controllers\Trip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Trip\Http\Requests\Trip\TripRequest;
use Modules\Trip\Services\Trip\TripInterface;
use Modules\Trip\Transformers\Trip\TripResource;
use Modules\Trip\Transformers\Trip\TripCollection;

class TripController extends Controller
{
    private TripInterface $tripInterface;

    public function __construct(TripInterface $tripInterface)
    {
        $this->tripInterface = $tripInterface;
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->tripInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripCollection($data),
            $code,
            $message
        );
    }
    public function myTrips(Request $request)
    {
        [$status, $data, $code, $message] = $this->tripInterface->myTrips($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripCollection($data),
            $code,
            $message
        );
    }

    public function store(TripRequest $request)
    {
        [$status, $data, $code, $message] = $this->tripInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->tripInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripResource($data),
            $code,
            $message
        );
    }

    public function update(TripRequest $request, $id)
    {
        [$status, $data, $code, $message] = $this->tripInterface->update($request, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripResource($data),
            $code,
            $message
        );
    }

    public function destroy($id)
    {
        [$status, $data, $code, $message] = $this->tripInterface->destroy($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }

    public function updateStatus($id, Request $request)
    {
        [$status, $data, $code, $message] = $this->tripInterface->updateStatus(
            $id,
            $request->input('status')
        );

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new TripResource($data),
            $code,
            $message
        );
    }
}