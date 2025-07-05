<?php

namespace Modules\User\Services\UserCRUD;

use Illuminate\Http\Request;

interface UserCRUDInterface
{
    public function changePassword(Request $request);
    public function updateProfile(Request $request);
    public function updateContactInfo(Request $request);
    public function avatar(Request $request);
    public function logo(Request $request);
    public function me();
}
