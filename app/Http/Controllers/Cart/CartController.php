<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Helpers\GetTotalCart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __invoke(){
        $cart = Cart::where('user_id' , Auth::id())->where('is_paid' , false)->first();
        if(!$cart){
            return response()->json([
                'status'   => true,
                'cart'     => [],
                'total'    => 0,
            ] , 200);
        }

        $subTotal = $cart->items->sum(function($item){
            return ($item->product->sale_price ? $item->product->sale_price : $item->product->regular_price) * $item->quantity;
        });

        $tax = round($subTotal * 0.08, 2);
        $total = $subTotal + $tax;
        return response()->json([
            'status'        => true,
            'cart'          => $cart,
            'subTotal'      => $subTotal,
            'tax'           => $tax,
            'total'         => $total,
            'totalItem'     => $cart->items->count(),
        ] , 200);
    }
}
