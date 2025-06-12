<?php


namespace Modules\Settings\Services\Currency;

use Illuminate\Http\Request;

interface CurrencyInterface
{
    public function index(Request $request);
    public function show($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function toggleDefault($id);
    public function default();
}