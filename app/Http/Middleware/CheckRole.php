<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $type)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $roleId = $user->role_id;

        // ADMIN AREA
        if ($type === 'admin') {
            if ($roleId != 5) {
                abort(403, 'Bạn không có quyền vào trang admin.');
            }
        }

        // USER AREA
        if ($type === 'user') {
            if ($roleId == 5) {
                abort(403, 'Admin không được vào trang user.');
            }
        }

        return $next($request);
    }
}