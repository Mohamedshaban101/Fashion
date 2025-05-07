<?php

namespace App\Http\Controllers\Admin\Categories;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class StoreCategoryController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'title'         => ['required' , 'string'],
            'description'   => ['required' , 'string'],
            'photo'         => ['required' , 'image' , 'mimes:jpeg,png,jpg']
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'errors'   => $validator->errors()
            ] , 422);
        }

        try {
            $validated = $validator->validated();
            $image = Storage::putFile('categories' , $validated['photo']);
            $category = Category::create([
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'photo'         => $image
            ]);
            return response()->json([
                'status'    => true,
                'message'   => 'category created successfully',
                'data'      => $category,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'    =>  false,
                'errors'    => $e->getMessage(),
            ] , 500);
        }
    }
}
