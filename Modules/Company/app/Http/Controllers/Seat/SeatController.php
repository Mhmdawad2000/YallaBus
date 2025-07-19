<?php

namespace Modules\Company\Http\Controllers\Seat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Http\Requests\Seat\SeatRequest;
use Modules\Company\Services\Seat\SeatInterface;
use Modules\Company\Transformers\Seat\SeatResource;
use Modules\Company\Transformers\Seat\SeatCollection;

class SeatController extends Controller
{
    private SeatInterface $seatInterface;

    public function __construct(SeatInterface $seatInterface)
    {
        $this->seatInterface = $seatInterface;
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->seatInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new SeatCollection($data),
            $code,
            $message
        );
    }

    public function store(SeatRequest $request)
    {
        [$status, $data, $code, $message] = $this->seatInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new SeatResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->seatInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new SeatResource($data),
            $code,
            $message
        );
    }

    public function update(SeatRequest $request, $id)
    {
        [$status, $data, $code, $message] = $this->seatInterface->update($request, $id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new SeatResource($data),
            $code,
            $message
        );
    }

    public function destroy($id)
    {
        [$status, $data, $code, $message] = $this->seatInterface->destroy($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }
}