<?php

namespace App\Http\Controllers\User;

use Exception;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateProfileController extends Controller
{
    public function __invoke(Request $request){
        $userInfo = UserInformation::where('id' , Auth::id())->first();
        $validator = Validator::make($request->all() , [
            'firstName' => 'string',
            'lastName'  => 'string',
            'email'     => 'email|unique:user_information,email,'.($userInfo->id ?? null ),
            'phone'     => 'string|min:11|max:15|regex:/^\+?[0-9]{11,15}$/',
            'address'   => 'string',
            'town'      => 'string',
            'state'     => 'string',
            'postal_code' => 'numeric',
            'photo'       => 'image|mimes:jpeg,png,jpg,gif'
        ]);
        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'erros'         => $validator->errors(),
            ] , 422);
        }
        try {
            $validated = $validator->validated();
            $path = $userInfo->photo ?? null;
            if($request->hasFile('photo')){
                if($path){
                    Storage::delete('storage/' , $path);
                }
                $path = Storage::putFile('profile' , $validated['photo']);
            }
            $updateUserInfo = UserInformation::updateOrCreate([
                'user_id'   => Auth::id(),
            ],[
                'firstName' => $validated['firstName'] ?? $userInfo->firstName,
                'lastName'  => $validated['lastName'] ?? $userInfo->lastName,
                'email'     => $validated['email'] ?? $userInfo->email,
                'phone'     => $validated['phone'] ?? $userInfo->phone,
                'address'   => $validated['address'] ?? $userInfo->address,
                'town'      => $validated['town'] ?? $userInfo->town,
                'state'     => $validated['state'] ?? $userInfo->state,
                'postal_code'=> $validated['postal_code'] ?? $userInfo->postal_code,
                'photo'     => $path,
                'country'   => 'Egypt'
            ]);
            return response()->json([
                'status'        => true,
                'message'       => 'profile updated successfully',
                'data'          => $updateUserInfo
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => true,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
