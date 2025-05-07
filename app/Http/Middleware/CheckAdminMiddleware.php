<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(! Auth::guard('api')->check()){
            return response()->json([
                'status'        => false,
                'error'         => 'Unauthorized. Please login first.',
            ] , 401);
        }

        if(Auth::guard('api')->user()->role !== 'ADM'){
            return response()->json([
                'status'        => false,
                'error'         => 'You are not authorized to access this resource.'
            ],403);
        }
        return $next($request);
    }
}
