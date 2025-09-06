<?php

namespace Modules\Settings\Http\Controllers\API\Complaint;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Settings\Models\Complaint;
use App\Http\Middleware\CheckPermission;
use Modules\Settings\Services\Complaint\ComplaintInterface;
use Modules\Settings\Http\Requests\Complaint\ComplaintRequest;
use Modules\Settings\Transformers\Complaint\ComplaintResource;
use Modules\Settings\Transformers\Complaint\ComplaintCollection;
use Modules\Settings\Http\Requests\Complaint\ComplaintResolveRequest;

class ComplaintController extends Controller
{
    private ComplaintInterface $complaintInterface;

    public function __construct(ComplaintInterface $complaintInterface)
    {
        $this->complaintInterface = $complaintInterface;
        $this->middleware(CheckPermission::class . ':create_complaint', ['only' => ['store']]);
        $this->middleware(CheckPermission::class . ':read_all_complaints', ['only' => ['index']]);
        $this->middleware(CheckPermission::class . ':read_complaint', ['only' => ['show']]);
        $this->middleware(CheckPermission::class . ':delete_complaint', ['only' => ['destroy']]);
        $this->middleware(CheckPermission::class . ':resolve_complaint', ['only' => ['resolve']]);
    }



    public function resolve(ComplaintResolveRequest $request, Complaint $complaint)
    {
        [$status, $result] = $this->complaintInterface->resolveOrClose($complaint, $request->validated());

        if ($status) {
            return $this->successResponse($result, 'Complaint updated successfully.');
        }

        return $this->errorResponse($result, 'Failed to update complaint.');
    }

    public function index(Request $request)
    {
        [$status, $data, $code, $message] = $this->complaintInterface->index($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ComplaintCollection($data),
            $code,
            $message
        );
    }

    public function store(ComplaintRequest $request)
    {
        [$status, $data, $code, $message] = $this->complaintInterface->store($request);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ComplaintResource($data),
            $code,
            $message
        );
    }

    public function show($id)
    {
        [$status, $data, $code, $message] = $this->complaintInterface->show($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(
            new ComplaintResource($data),
            $code,
            $message
        );
    }

    // public function update(ComplaintRequest $request, $id)
    // {
    //     [$status, $data, $code, $message] = $this->complaintInterface->update($request, $id);

    //     if (!$status) {
    //         return $this->errorResponse($data, $code, $message);
    //     }

    //     return $this->successResponse(
    //         new ComplaintResource($data),
    //         $code,
    //         $message
    //     );
    // }

    public function destroy($id)
    {
        [$status, $data, $code, $message] = $this->complaintInterface->destroy($id);

        if (!$status) {
            return $this->errorResponse($data, $code, $message);
        }

        return $this->successResponse(null, $code, $message);
    }
}
