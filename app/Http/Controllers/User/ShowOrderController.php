<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\UserInformation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShowOrderController extends Controller
{
    public function __invoke($id){
        $order = Order::where('id' , $id)->where('user_id' , Auth::id())->first();
        $userInfo = UserInformation::where('user_id' , Auth::id())->first();
        $items = $order->items->map(function($item){
            return $item->product;
        });

        return response()->json([
            'status'        => true,
            'data'          => [
                'order'    => $order,
                'userInfo' => $userInfo,
                'items'    => $items, 
            ]
        ] , 200);
    }
}
