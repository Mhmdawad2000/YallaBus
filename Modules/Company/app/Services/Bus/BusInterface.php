<?php

namespace Modules\Company\Services\Bus;

use Illuminate\Http\Request;
use Modules\Company\Http\Requests\Bus\BusRequest;

interface BusInterface
{
    public function index(Request $request);
    public function store(BusRequest $request);
    public function show($id);
    public function update(BusRequest $request, $id);
    public function destroy($id);
}