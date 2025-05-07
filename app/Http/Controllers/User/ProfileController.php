<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __invoke(){
        $user = User::select('email')->where('id' , Auth::id())->first();
        $userInfo = UserInformation::where('id' , Auth::id())->first();
        return response()->json([
            'status'        => true,
            'data'          => [
                'user'      => $user,
                'userInfo'  => $userInfo,
            ],
        ],200);
    }
}
