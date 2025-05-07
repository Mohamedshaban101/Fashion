<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\BaseController;

class GithubSocialiteController extends BaseController
{
    public function redirectToGithub(){
        return Socialite::driver('github')->stateless()->redirect()->getTargetUrl();
    }

    public function handleGithub(){
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $user = User::updateOrCreate([
                'email'     => $githubUser->getEmail(),
            ],[
                'username'  => $githubUser->getName(),
                'provider_id'     => $githubUser->getId(),
                'provider_name'   => 'github',
                'password'  => Hash::make($githubUser->getName()),
            ]);
            $token = auth()->login($user);
            return $this->sendResponse('github login successfully' , $user , $token , 200);
        } catch (Exception $e) {
            return $this->sendError('failed to login' , $e->getMessage() , 500);
        }
    }
}
