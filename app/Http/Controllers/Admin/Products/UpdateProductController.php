<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateProductController extends Controller
{
    public function __invoke(Request $request , $id){
        $validator = Validator::make($request->all() , [
            'name'          => ['required' , 'string'],
            'description'   => ['required' , 'max:255'],
            'regular_price' => ['required' , 'numeric'],
            'sale_price'    => ['nullable' , 'numeric'],
            'discount'      => ['nullable' , 'numeric'],
            'quantity'      => ['required' , 'integer'],
            'photo'         => ['nullable' , 'image' , 'mimes:jpg,png,jpeg'],
            'category_id'   => ['required' , 'exists:categories,id'],
        ]);

        if($validator->fails()){
            return reponse()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }

        try {
            $validated = $validator->validated();
            $product = Product::where('id' , $id)->first();

            if(!$product){
                return response()->json([
                    'status'        => true,
                    'message'       => 'product not found',
                ],404);
            }
            
            if($request->hasFile('photo')){
                Storage::delete('storage/' , $product->photo);
                $path = Storage::putFile('products' , $validated['photo']);
            }else{
                $path = $product->photo;
            }

            $product->update([
                'name'          => $validated['name'],
                'description'   => $validated['description'],
                'regular_price' => $validated['regular_price'],
                'sale_price'    => $validated['sale_price'],
                'discount'      => $validated['discount'],
                'quantity'      => $validated['quantity'],
                'photo'         => $path,
                'category_id'   => $validated['category_id'],
            ]);
            $product->colors()->sync($request->color);
            return response()->json([
                'status'        => true,
                'message'       => 'product updated successfully',
                'data'          => $product,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage()
            ] , 500);
        }
    }
}
