<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $user_type): Response
    {   
        if($user_type == 'admin') {
            $user_type = 0;
        } else {
            $user_type = 1;
        }
        
        if(!auth()->check() || auth()->user()->user_type !== $user_type) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
