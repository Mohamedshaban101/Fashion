<?php

namespace App\Http\Controllers\Admin\Categories;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateCategoryController extends Controller
{
    public function __invoke(Request $request , $id){
        $validator = Validator::make($request->all() , [
            'title'         => ['required' , 'string'],
            'description'   => ['required' , 'string'],
            'photo'         => ['image' , 'mimes:jpeg,png,jpg']
        ]);
        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }
        try {
            $validated = $validator->validated();
            $category = Category::where('id' , $id)->first();
            if(!$category){
                return response()->json([
                    'status'        => false,
                    'message'       => 'category not found',
                ] , 404);
            }
            if($request->hasFile('photo')){
                Storage::delete('storage/' , $category->photo);
                $path = Storage::putFile('categories/' , $validated['photo']);
            }else{
                $path = $category->photo;
            }
            $category->update([
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'photo'         => $path,
            ]);
            return response()->json([
                'status'        => true,
                'message'       => 'category created successfully',
                'data'          => $category,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
