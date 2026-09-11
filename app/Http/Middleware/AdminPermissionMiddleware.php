<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPermissionMiddleware
{
    /**
     * Handle an incoming request and ensure the authenticated user has required permission.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('admin.login');
        }

        // If no specific permission requested, allow access
        if (empty($permissions)) {
            return $next($request);
        }

        // Check if user has any of the requested permissions
        if (! $user->hasPermission($permissions)) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden: You do not have permission to access this module.',
                ], 403);
            }

            abort(403, 'Unauthorized: You do not have permission to access this module.');
        }

        return $next($request);
    }
}
