<?php

namespace Modules\Booking\Http\Controllers\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Booking\Services\Booking\BookingInterface;
use Modules\Booking\Http\Requests\Booking\BookingRequest;
use Modules\Booking\Transformers\Booking\BookingResource;
use Modules\Booking\Transformers\Booking\BookingCollection;

class BookingController extends Controller
{
    private BookingInterface $bookingInterface;

    public function __construct(BookingInterface $bookingInterface)
    {
        $this->bookingInterface = $bookingInterface;
        $this->middleware(CheckPermission::class . ':create_booking', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':read_all_bookings', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_my_bookings', ['only' => ['meIndex']]);
        $this->middleware(CheckPermission::class . ':read_booking', ['only' => ['show']]);
        $this->middleware(CheckPermission::class . ':delete_booking', ['only' => ['destroy']]);
        $this->middleware(CheckPermission::class . ':cancel_booking', ['only' => ['cancel']]);
        $this->middleware(CheckPermission::class . ':change_status_booking', ['only' => ['status']]);
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