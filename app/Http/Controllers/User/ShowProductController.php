<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowProductController extends Controller
{
    public function __invoke($id){
        $product = Product::where('id' , $id)->first();
        if(!$product){
            return response()->json([
                'status'        => true,
                'message'       => 'product not found',
            ] , 404);
        }
        return response()->json([
            'status'        => true,
            'data'          => $product,
            'colors'        => $product->colors 
        ] , 200);
    }
}
