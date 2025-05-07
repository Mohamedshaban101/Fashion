<?php

namespace App\Http\Controllers\Admin\Brands;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateBrandController extends Controller
{
    public function __invoke(Request $request , $id){
        $validator = Validator::make($request->all() , [
            'title'         => ['required' , 'string'],
            'description'   => ['required' , 'string' , 'max:255'],
            'photo'         => ['image' , 'mimes:jpg,png,jpeg'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }
        try {
            $validated = $validator->validated();
            $brand = Brand::where('id' , $id)->first();
            if(!$brand){
                return response()->json([
                    'status'        => true,
                    'message'       => 'brand not found'
                ] , 404);
            }
            if($request->hasFile('photo')){
                Storage::delete('storage/' , $brand->photo);
                $path = Storage::putFile('brands' , $validated['photo']);
            }else{
                $path = $brand->photo;
            }
            $brand->update([
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'photo'         => $path,
            ]);
            return response()->json([
                'status'        => true,
                'message'       => 'brand updated successfully',
                'data'          => $brand,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
