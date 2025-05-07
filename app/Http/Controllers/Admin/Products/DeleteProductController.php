<?php

namespace App\Http\Controllers\Admin\Products;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DeleteProductController extends Controller
{
    public function __invoke($id){
        try {
            $product = Product::where('id' , $id)->first();
            if(!$product){
                return response()->json([
                    'status'        => true,
                    'message'       => 'product not found',
                ],404);
            }

            Storage::delete('storage/' , $product->photo);
            $product->delete();
            return response()->json([
                'status'        => true,
                'message'       => 'product deleted successfully',
            ] , 200);
        } catch (Exception $e) {
            return response()->json([
                'status'        => false,
                'errors'        => $e->getMessage(),
            ] , 500);
        }
    }
}
