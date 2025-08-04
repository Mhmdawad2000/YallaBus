<?php

namespace Modules\Trip\Services\Trip;

use Illuminate\Http\Request;
use Modules\Trip\Http\Requests\Trip\TripRequest;

interface TripInterface
{
    public function index(Request $request);
    public function myTrips(Request $request);
    public function store(TripRequest $request);
    public function show($id);
    public function update(TripRequest $request, $id);
    public function destroy($id);
    public function updateStatus($id, $status);
}