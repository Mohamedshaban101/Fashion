<?php

namespace App\Http\Controllers\Admin\Brands;

use Exception;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DeleteBrandController extends Controller
{
    public function __invoke($id){
        try {
            $brand = Brand::where('id' , $id)->first();
            if(!$brand){
                return response()->json([
                    'status'        => true,
                    'message'       => 'brand not found',
                ] , 404);
            }
            Storage::delete('storage/' , $brand->photo);
            $brand->delete();
            return response()->json([
                'status'        => true,
                'message'       => 'brand deleted successfully',
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
