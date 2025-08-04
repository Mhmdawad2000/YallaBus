<?php

namespace Modules\Company\Services\Seat;

use Illuminate\Http\Request;
use Modules\Company\Http\Requests\Seat\SeatRequest;

interface SeatInterface
{
    public function index(Request $request);
    public function store(SeatRequest $request);
    public function show($id);
    public function update(SeatRequest $request, $id);
    public function destroy($id);
}