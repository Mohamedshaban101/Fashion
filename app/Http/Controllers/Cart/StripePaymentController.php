<?php

namespace App\Http\Controllers\Cart;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StripePaymentController extends Controller
{
    
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name_on_card'  => ['required' , 'string'],
        ]);
        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $cart = Cart::where('user_id' , Auth::id())->where('is_paid' , false)->first();
            if (!$cart) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart not found',
                ], 404);
            }
            $subTotal = $cart->items->sum(function($item){
                return ($item->product->sale_price ? $item->product->sale_price : $item->product->regular_price) * $item->quantity;
            });
            $tax = round($subTotal * 0.08 , 2);
            $total = $subTotal + $tax;
            $paymentIntent = PaymentIntent::create([
                'amount'    => $total * 100,
                'currency'  => 'usd',
                'description' => 'payment from user id : '.Auth::id(),
                'metadata'      => ['user_id' => Auth::id()],
                'payment_method_types' => ['card'],
            ]);

            $order = Order::create([
                'user_id'       => Auth::id(),
                'subtotal'      => $subTotal,
                'tax'           => $tax,
                'total'         => $total,
                'status'        => 'pending',
                'total_item'    => $cart->items->count(),
            ]);
            foreach($cart->items as $item){
                $product = Product::where('id' , $item->id)->first();
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'quantity'      => $item->quantity,
                    'price'         => $item->product_price,
                ]);
                $product->decrement('quantity' , $item->quantity);
            }
            $payment = Payment::create([
                'stripe_charge_id'  => $paymentIntent->id,
                'amount'            => $paymentIntent->amount / 100,
                'currency'          => $paymentIntent->currency,
                'status'            => $paymentIntent->status,
                'description'       => $paymentIntent->description,
                'user_id'           => Auth::id(),
            ]);

            $cart->is_paid = true;
            $cart->save();
            return response()->json([
                'status'        => true,
                'message'       => 'Payment intent successfully',
                'data'          => $payment,
                'client_secret' => $paymentIntent->client_secret,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
