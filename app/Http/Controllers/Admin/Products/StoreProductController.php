<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreProductController extends Controller
{
    public function __invoke(Request  $request){
        $categories = Category::all();

        if($categories->count() == 0){
            return response()->json([
                'status'        => true,
                'message'       => 'please create category first',
            ] , 403);
        }
        $validator = Validator::make($request->all() , [
            'name'          => ['required' , 'string'],
            'description'   => ['required' , 'string' , 'max:255'],
            'regular_price' => ['required' , 'numeric'],
            'sale_price'    => ['nullable','numeric'],
            'discount'      => ['nullable','numeric'],
            'quantity'      => ['required' , 'integer'],
            'photo'         => ['required' , 'image' , 'mimes:jpg,png,jpeg'],
            'category_id'   => ['required' , 'exists:categories,id']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'         => $validator->errors(),
            ],422);
        }

        try {
            $validated = $validator->validated();
            $image = Storage::putFile('products' , $validated['photo']);
            $product = Product::create([
                'name'          => $validated['name'],
                'description'   => $validated['description'],
                'regular_price' => $validated['regular_price'],
                'sale_price'    => $validated['sale_price'],
                'discount'      => $validated['discount'],
                'quantity'      => $validated['quantity'],
                'photo'         => $image,
                'category_id'   => $validated['category_id'],
            ]);
            $product->colors()->attach($request->color);
            return response()->json([
                'status'        => true,
                'message'       => 'product created successfully',
                'data'         => $product,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),  
            ] , 500);
        }
    }
}
