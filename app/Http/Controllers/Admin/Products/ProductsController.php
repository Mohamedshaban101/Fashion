<?php

namespace App\Http\Controllers\Admin\Products;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    public function __invoke(){
        $products = Product::orderBy('created_at' , 'ASC')->paginate(10);
        if($products){
            $response = ProductResource::collection($products);
            return response()->json([
                'status'        => true,
                'data'          => [
                    'products'      => $response,
                    'pagination'  => [
                        'total'         => $products->total(),
                        'per_page'      => $products->perPage(),
                        'current_page'  => $products->currentPage(),
                        'last_page'     => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),    
                    ]
                ],
            ],200);
        }else{
            return response()->json([
                'status'        => false,
                'message'         => 'products not found',
            ], 404);
        }
    }
}
