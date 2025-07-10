<?php

namespace Modules\Settings\Http\Controllers\Review;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckPermission;
use Modules\Settings\Services\Review\ReviewInterface;
use Modules\Settings\Http\Requests\Review\ReviewRequest;
use Modules\Settings\Transformers\Review\ReviewResource;
use Modules\Settings\Transformers\Review\ReviewCollection;

class ReviewController extends Controller
{
    private ReviewInterface $reviewInterface;

    public function __construct(ReviewInterface $reviewInterface)
    {
        $this->reviewInterface = $reviewInterface;
        $this->middleware(CheckPermission::class . ':create_review', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':read_all_reviews', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_review', ['only' => ['show']]);
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->reviewInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ReviewCollection($data),
            $code,
            $message
        );
    }

    public function store(ReviewRequest $request)
    {
        [$status, $data, $code, $message] = $this->reviewInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ReviewResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->reviewInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ReviewResource($data),
            $code,
            $message
        );
    }

    // public function update(ReviewRequest $request, $id)
    // {
    //     [$status, $data, $code, $message] = $this->reviewInterface->update($request, $id);

    //     if (!$status) {
    //         return $this->errorResponse($data, $code, $message);
    //     }

    //     return $this->successResponse(
    //         new ReviewResource($data),
    //         $code,
    //         $message
    //     );
    // }

    // public function destroy($id)
    // {
    //     [$status, $data, $code, $message] = $this->reviewInterface->destroy($id);

    //     if (!$status) {
    //         return $this->errorResponse($data, $code, $message);
    //     }

    //     return $this->successResponse(null, $code, $message);
    // }
}