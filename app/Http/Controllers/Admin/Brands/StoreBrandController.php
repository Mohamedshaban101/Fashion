<?php

namespace App\Http\Controllers\Admin\Brands;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StoreBrandController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'title'         => ['required' , 'string'],
            'description'   => ['required' , 'string' , 'max:255'],
            'photo'         => ['required' , 'image' , 'mimes:jpg,png,jpeg'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }

        try {
            $validated = $validator->validated();
            $image = Storage::putFile('brands' , $validated['photo']);
            $brand = Brand::create([
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'photo'         => $image,
            ]);
            return response()->json([
                'status'        => true,
                'message'       => 'brand created successfully',
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
