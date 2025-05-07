<?php

namespace App\Http\Controllers\Cart;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateCartController extends Controller
{
    public function __invoke(Request $request){
        $cart = CartItem::where('id' , $request->id)->first();
        $cart->update([
            'quantity'      => $request->quantity,
        ]);
        return response()->json([
            'status'        => true,
            'message'       => 'cart updated successfully',
        ] , 200);
    }
}
