<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

class ProductsController extends Controller
{
    public function __invoke(){
        $products = Product::orderBy('created_at' , 'ASC')->paginate(10);
        $categories = Category::orderBy('created_at' , 'ASC')->paginate(10);
        if($products->isEmpty() && $categories->isEmpty()){
            return response()->json([
                'status'        => true,
                'message'       => 'products and categories not found',
            ] , 404);
        }else{
            $products = ProductResource::collection($products);
            $categories = CategoryResource::collection($categories);
            return response()->json([
                'status'        => true,
                'products'      => [
                    'data'          => $products,
                    'pagination'    => [
                        'total'         => $products->total(),
                        'per_page'      => $products->perPage(),
                        'current-page'  => $products->currentPage(),
                        'last_page'     => $products->lastPage(),
                        'next_page_url' => $products->nextPageUrl(),
                        'prev_page_url' => $products->previousPageUrl(),
                    ]
                ],
                'categories'    => [
                    'data'          => $categories,
                ],
            ],200);
        }
    }
}
