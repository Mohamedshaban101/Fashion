<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Auth\BaseController;
use Symfony\Component\HttpFoundation\Response;

class JWtAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if(!$user){
                return BaseController::sendError('Unauthorized. Please login first.', [], 404);
            }
        } catch (JWTException $e) {
            return BaseController::sendError('Token not valid' , $e->getMessage() , 401);
        }
        return $next($request);
    }
}
