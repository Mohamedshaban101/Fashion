<?php

namespace App\Http\Controllers\User;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;

class HomeController extends Controller
{
    public function __invoke(){
        $products = Product::orderBy('created_at' , 'ASC')->get();
        $categories = Category::orderBy('created_at' , 'ASC')->get();
        $brands = Brand::orderBy('created_at' , 'ASC')->get();
        if($products->isEmpty() && $categories->isEmpty() || $brands->isEmpty()){
            return response()->json([
                'status'        => true,
                'message'       => 'products , categories and brands not found',
            ] , 404);
        }else{
            $products = ProductResource::collection($products);
            $categories = CategoryResource::collection($categories);
            $brands = BrandResource::collection($brands);
            return response()->json([
                'status'        => true,
                'products'      => $products,
                'categories'    => $categories,
                'brands'        => $brands,
            ],200);
        }
    }
}
