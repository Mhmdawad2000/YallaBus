<?php

namespace Modules\Booking\Services\Booking;

use Illuminate\Http\Request;
use Modules\Booking\Http\Requests\Booking\BookingRequest;

interface BookingInterface
{
    public function index(Request $request);
    public function meIndex(Request $request);
    public function store(BookingRequest $request);
    public function show($id);
    // public function update(BookingRequest $request, $id);
    public function destroy($id);
    public function cancel($id, Request $request);
    // public function status($id, Request $request);
}