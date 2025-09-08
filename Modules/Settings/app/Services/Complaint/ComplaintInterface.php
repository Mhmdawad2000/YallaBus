<?php

namespace Modules\Settings\Services\Complaint;

use Illuminate\Http\Request;
use Modules\Settings\Models\Complaint;
use Modules\Settings\Http\Requests\Complaint\ComplaintRequest;

interface ComplaintInterface
{

    public function resolveOrClose(Complaint $complaint, array $data): array;

    public function index(Request $request);
    public function store(ComplaintRequest $request);
    public function show($id);
    // public function update(ComplaintRequest $request, $id);
    public function destroy($id);
}
