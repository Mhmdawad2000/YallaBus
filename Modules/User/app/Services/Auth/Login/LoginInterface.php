<?php

namespace Modules\User\Services\Auth\Login;

use Illuminate\Http\Request;

interface LoginInterface
{
    public function login(Request $request): array;
}
