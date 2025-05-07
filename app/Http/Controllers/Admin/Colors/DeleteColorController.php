<?php

namespace App\Http\Controllers\Admin\Colors;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleteColorController extends Controller
{
    public function __invoke($id){
        $color = Color::where('id' , $id)->first();

        if($color){
            $color->delete();
            return response()->json([
                'status'        => true,
                'message'       => 'color deleted successfully',
            ] , 200);
        }
        return response()->json([
            'status'        => true,
            'message'       => 'color not found',
        ] , 404);
    }
}
