<?php

namespace App\Http\Middleware;

use Closure;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class CheckPermission
{
    public function handle($request, Closure $next, $name)
    {
        $user = User::find(Auth::id());

        $permission = Permission::where('name', $name)->first();

        if (!$permission || !$user->hasPermissionTo($permission->name)) {
            return response()->json(['data' => [], 'status' => 403, 'message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }


}
