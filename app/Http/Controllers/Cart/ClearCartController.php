<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClearCartController extends Controller
{
    public function __invoke(Request $request){
        $cart = Cart::where('user_id' , Auth::id())->where('is_paid' , false)->first();
        $cart->delete();
        return response()->json([
            'status'        => true,
            'message'       => 'cart removed successfully',
        ] , 200);
    }
}
