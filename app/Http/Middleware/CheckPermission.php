<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        if (!auth()->user()->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'ไม่มีสิทธิ์เข้าถึงส่วนนี้'], 403);
            }

            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้ กรุณาติดต่อผู้ดูแลระบบ');
        }

        return $next($request);
    }
}
