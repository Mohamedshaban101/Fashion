<?php

namespace App\Http\Controllers\Admin\Colors;

use Exception;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreColorController extends Controller
{
    public function __invoke(Request $request){
        $validator = Validator::make($request->all() , [
            'name'      => ['required' , 'string'],
            'code'      => ['required' , 'string' , 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        if($validator->fails()){
            return response()->json([
                'status'        => false,
                'errors'        => $validator->errors(),
            ] , 422);
        }

        try {
            $validated = $validator->validated();
            $color = Color::create([
                'name'      => $validated['name'],
                'code'      => $validated['code'],
            ]);
            return response()->json([
                'status'        => true,
                'message'       => 'color created successfully',
                'color'         => $color,
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
