<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $_status = false;
        foreach (Auth::user()->roles as $key => $role) {
            if ($role->id == 7) {
                $_status = true;
            }
        }
        return $_status ? $next($request) : redirect('/');
    }
}
