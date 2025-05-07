<?php

namespace App\Http\Controllers\Admin\Categories;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DeleteCategoryController extends Controller
{
    public function __invoke($id){
        try {
            $category = Category::where('id' , $id)->first();
            if(!$category){
                return response()->json([
                    'status'        => false,
                    'message'       => 'category not found'
                ] , 404);
            }
            Storage::delete('storage/',$category->photo);
            $category->delete();
            return response()->json([
                'status'        => true,
                'message'       => 'category delete successfully',
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
