<?php

namespace Modules\Settings\Services\Review;

use Illuminate\Http\Request;
use Modules\Settings\Http\Requests\Review\ReviewRequest;

interface ReviewInterface
{
    public function index(Request $request);
    public function store(ReviewRequest $request);
    public function show($id);
    // public function update(ReviewRequest $request, $id);
    // public function destroy($id);
}