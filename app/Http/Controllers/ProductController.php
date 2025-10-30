<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller{
    public function getProductsByUmkmId($umkmId){
        $product = Product::where('umkmId', $umkmId)->get();
        if($product){
            return response()->json($product, 200);
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }


}
