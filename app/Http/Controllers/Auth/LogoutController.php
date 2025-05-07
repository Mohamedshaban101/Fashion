<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Auth\BaseController;

class LogoutController extends BaseController
{
    public function __invoke(Request $request){
        try {
            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);
            return $this->sendResponse('User Logout successfully' , JWTAuth::getToken() , 200);
        } catch (JWTException $e) {
            return $this->sendError('failed to logout' , $e->getMessage());
        }
    }
}
