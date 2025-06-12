<?php

namespace Modules\Settings\Services\Country;

use Illuminate\Http\Request;

interface CountryInterface
{
    public function index(Request $request): array;
    public function store(array $data): array;
    public function show($id): array;
    public function update($id, array $data): array;
    public function delete($id): array;
}
