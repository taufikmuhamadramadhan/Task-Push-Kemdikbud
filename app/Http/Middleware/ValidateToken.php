<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Application;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token && Application::where('token', hash('sha256', $token))->exists()) {
            return $next($request);
        }

        // Redirect ke halaman login jika token tidak valid atau tidak ada
        if (!$request->expectsJson()) {
            return redirect()->guest('login');
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
