<?php

namespace Modules\Booking\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Booking\Http\Requests\Booking\BookingRequest;
use Modules\Booking\Services\Booking\BookingInterface;
use Modules\Booking\Transformers\Booking\BookingResource;
use Modules\Booking\Transformers\Booking\BookingCollection;

class BookingController extends Controller
{
    private BookingInterface $bookingInterface;

    public function __construct(BookingInterface $bookingInterface)
    {
        $this->bookingInterface = $bookingInterface;
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingCollection($data),
            $code,
            $message
        );
    }
    public function meIndex(Request $request)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->meIndex($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingCollection($data),
            $code,
            $message
        );
    }

    public function store(BookingRequest $request)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingResource($data),
            $code,
            $message
        );
    }

    // public function update(BookingRequest $request, $id)
    // {
    //     [$status, $data, $code, $message] = $this->bookingInterface->update($request, $id);

    //     if (!$status) {
    //         return $this->errorResponse($data, $code, $message);
    //     }

    //     return $this->successResponse(
    //         new BookingResource($data),
    //         $code,
    //         $message
    //     );
    // }

    public function destroy($id)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->destroy($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }

    public function cancel($id, Request $request)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->cancel(
            $id,
            $request
        );

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingResource($data),
            $code,
            $message
        );
    }
    public function status($id, Request $request)
    {
        [$status, $data, $code, $message] = $this->bookingInterface->status(
            $id,
            $request
        );

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new BookingResource($data),
            $code,
            $message
        );
    }
}