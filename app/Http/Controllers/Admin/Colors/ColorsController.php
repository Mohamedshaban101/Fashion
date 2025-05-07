<?php

namespace App\Http\Controllers\Admin\Colors;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;

class ColorsController extends Controller
{
    public function __invoke(){
        $colors = Color::orderBy('created_at' , 'ASC')->paginate(5);
        if($colors){
            $response = ColorResource::collection($colors);
            return response()->json([
                'status'    => true,
                'data'      => [
                    'colors'    => $response,
                    'pagination'=> [
                        'total'         => $colors->total(),
                        'per_page'      => $colors->perPage(),
                        'current_page'  => $colors->currentPage(),
                        'last_page'     => $colors->lastPage(),
                        'next_page_url' => $colors->nextPageUrl(),
                        'prev_page_url' => $colors->previousPageUrl(),
                    ]
                ],
            ] , 200);
        }
        return response()->json([
            'status'        => true,
            'message'       => 'data not found',
        ] , 404);
    }
}
