<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\BaseController;

class GoogleSocialiteController extends BaseController
{
    public function redirectTo(){
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function handle(){
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate([
                'email'     => $googleUser->getEmail(),
            ],[
                'username'      => $googleUser->getName(),
                'provider_id'   => $googleUser->getId(),
                'provider_name' => 'google',
                'password'  => Hash::make($googleUser->getName())
            ]);
            $token = auth()->login($user);
            return $this->sendResponse('google login successfully' , $user , $token , 200);
        } catch (Exception $e) {
            return $this->sendError(
                'user failed to login',
                $e->getMessage(),
                500
            );
        }
    }
}
