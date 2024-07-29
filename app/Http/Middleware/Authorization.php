<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = Session::get('api_token');

        if (!$token) {
            return redirect()->route('login.form');
        }

        // Cek token di database
        $user = DB::table('applications')
            ->where('token', hash('sha256', $token))
            ->where('expired_at', '>', now())
            ->first();

        if (!$user) {
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
