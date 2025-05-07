<?php

namespace App\Http\Controllers\Cart;

use Exception;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlaceOrderController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'firstName'     => ['required' , 'string' , 'max:255'],
            'lastName'      => ['required' , 'string' , 'max:255'],
            'email'         => ['required' , 'email' , 'max:255'],
            'phone'         => ['required' , 'string' , 'min:11' , 'max:15' , 'regex:/^\+?[0-9]{11,15}$/'],
            'address'       => ['required' , 'string'],
            'town'          => ['required' , 'string'],
            'state'         => ['required' , 'string'],
            'postal_code'   => ['required' , 'numeric'],
            'country'       => ['required' , 'string'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),       
            ] , 422);
        }
        try {
            $validated = $validator->validated();
            $userInfo = UserInformation::updateOrCreate([
                'user_id'       => Auth::id(),
            ],[
                'firstName'     => $validated['firstName'],
                'lastName'      => $validated['lastName'],
                'email'         => $validated['email'],
                'phone'         => $validated['phone'],
                'address'       => $validated['address'],
                'town'          => $validated['town'],
                'state'         => $validated['state'],
                'postal_code'   => $validated['postal_code'],
                'country'       => $validated['country'],
            ]);
            // $cart = Cart::where('user_id' , Auth::id())->first();

            // $subTotal = $cart->items->sum(function($item){
            //     return ($item->product->sale_price ? $item->product->sale_price : $item->product->regular_price) * $item->quantity;
            // });
            // $tax = round($subTotal * 0.08 , 2);
            // $total = $subTotal + $tax;
            return response()->json([
                'status'        => true,
                'userInfo'      => $userInfo,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
