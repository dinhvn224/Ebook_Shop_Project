<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check()) {
    $userRole = Auth::user()->role;

    // Nếu middleware yêu cầu 'user' nhưng user là 'admin' thì cũng cho vào
    if ($role === 'user' && in_array($userRole, ['user', 'admin'])) {
        return $next($request);
    }

    // Nếu cần 'admin' thì phải là admin đúng nghĩa
    if ($role === $userRole) {
        return $next($request);
    }
}
abort(403, 'Không có quyền truy cập');

    }

}
