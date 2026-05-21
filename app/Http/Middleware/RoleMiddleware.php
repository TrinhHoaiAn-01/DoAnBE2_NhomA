<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // admin = role_id 5
        if ($role === 'admin' && $user->role_id != 5) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        // user = role_id 1-4
        if ($role === 'user' && $user->role_id == 5) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}