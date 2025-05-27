<?php

namespace App\Http\Controllers\Auth;

use JWTException;
use Illuminate\Http\Request;
use App\Mail\WelcomeUserMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
// use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\BaseController;

class LoginController extends BaseController
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'username'      => ['required' , 'string'],
            'password'      => ['required' , 'string']
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error' , $validator->errors());
        }

        try {
            $credentials = $request->only('username' , 'password');
            if(! $token = JWTAuth::attempt($credentials)){
                return $this->sendError('Ivalid credentials' , [] , 403);
            }
            $user = auth()->user();
            return $this->sendResponse('User Login Successfully' , $user , $token , 200);
        } catch (JWTException $e) {
            return $this->sendError('Could not create token' , $e->getMessage());
        }
    }
}
