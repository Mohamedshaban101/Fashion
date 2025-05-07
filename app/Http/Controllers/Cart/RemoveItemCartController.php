<?php

namespace App\Http\Controllers\Cart;

use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RemoveItemCartController extends Controller
{
    public function __invoke($id){
        $cart = CartItem::where('id' , $id)->first();
        $cart->delete();
        return response()->json([
            'status'        => true,
            'message'       => 'item removed from cart',
        ] , 200);
    }
}
