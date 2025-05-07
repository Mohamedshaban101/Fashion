<?php

namespace App\Http\Controllers\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddToCartController extends Controller
{
    public function __invoke(Request $request){
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() , 'is_paid' => false]);

        $item = CartItem::where('cart_id' , $cart->id)->where('product_id' , $request->product_id)->first();
        $product = Product::where('id' , $request->product_id)->first();
        if(!$product){
            return response()->json([
                'status'        => true,
                'message'       => 'product not found to add to cart',
            ],404);
        }
        if($item){
            $item->increment('quantity');
            $item->update([
                'product_price' => ($item->sale_price ?  $item->sale_price : $item->regular_price) * $request->quantity
            ]);
        }else{
            CartItem::create([
                'cart_id'   => $cart->id,
                'product_id'=> $request->product_id,
                'quantity'  => $request->quantity,
                'product_price'=> ($product->sale_price ? $product->sale_price : $product->regular_price ) * $request->quantity,
                'color'     => $request->color,
            ]);
        }

        return response()->json([
            'status'        => true,
            'message'       => 'product has been added to cart',
        ] , 200);
    }
}
