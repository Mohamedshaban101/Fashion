<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoriesController extends Controller
{
    public function __invoke(){
        $categories = Category::orderBy('created_at' , 'ASC')->paginate(5);
        if($categories){
            $response = CategoryResource::collection($categories);
            return response()->json([
                'status'    => true,
                'data'      => [
                    'categories'  => $response,
                    'pagination'  => [
                        'total'         => $categories->total(),
                        'per_page'      => $categories->perPage(),
                        'current_page'  => $categories->currentPage(),
                        'last_page'     => $categories->lastPage(),
                        'next_page_url' => $categories->nextPageUrl(),
                        'prev_page_url' => $categories->previousPageUrl(),    
                    ]
                ]
            ] , 200);
        }else{
            return response()->json([
                'status'    => true,
                'error'      => 'data not found'
            ] , 404);
        }
    }
}
