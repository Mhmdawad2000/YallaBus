<?php

namespace Modules\Company\Services\Driver;

use Illuminate\Http\Request;
use Modules\Company\Http\Requests\Driver\DriverRequest;

interface DriverInterface
{
    public function index(string $role, Request $request);
    public function store(string $role, DriverRequest $request);
    public function show(string $role, $id);
    public function update(string $role, DriverRequest $request, $id);
    public function destroy(string $role, $id);
}
