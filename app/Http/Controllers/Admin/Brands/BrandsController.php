<?php

namespace App\Http\Controllers\Admin\Brands;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;

class BrandsController extends Controller
{
    public function __invoke(){
        $brands = Brand::orderBy('created_at' , 'ASC')->paginate(10);
        if($brands){
            $response = BrandResource::collection($brands);
            return response()->json([
                'status'    => true,
                'brands'    => [
                    'data'          => $response,
                    'pagination'    => [
                        'total'         => $brands->total(),
                        'per_page'      => $brands->perPage(),
                        'current_page'  => $brands->currentPage(),
                        'last_page'     => $brands->lastPage(),
                        'next_page_url' => $brands->nextPageUrl(),
                        'prev_page_url' => $brands->previousPageUrl(),
                    ]
                ]
            ],200);
        }

        return response()->json([
            'status'        => true,
            'message'       => 'brand not found',
        ],404);
    }
}
