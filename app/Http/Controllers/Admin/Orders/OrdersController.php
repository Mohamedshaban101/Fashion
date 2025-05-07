<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function __invoke(){
        $orders = Order::orderBy('created_at' , 'ASC')->paginate(5);
        if(!$orders){
            return response()->json([
                'status'    => true,
                'message'   => 'orders not found',
            ] , 404);
        }
        return response()->json([
            'status'        => true,
            'data'          => [
                'orders'        => $orders,
                'pagination' => [
                    'total'         => $orders->total(),
                    'per_page'      => $orders->perPage(),
                    'current_page'  => $orders->currentPage(),
                    'last_page'     => $orders->lastPage(),
                    'next_page_url' => $orders->nextPageUrl(),
                    'prev_page_url' => $orders->previousPageUrl(),
                ]
            ]
        ] , 200);
    }
}
