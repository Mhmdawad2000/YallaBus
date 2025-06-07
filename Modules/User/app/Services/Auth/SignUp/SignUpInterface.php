<?php

namespace Modules\User\Services\Auth\SignUp;

use Illuminate\Http\Request;

interface SignUpInterface
{
    public function signUpUser(array $data): array;
    public function verify(Request $request): array;
}
