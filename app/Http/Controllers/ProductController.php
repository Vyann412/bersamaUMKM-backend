<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Log;

class ProductController extends Controller{
    public function getProductsByUmkmId($umkmId){
        $product = Product::where('umkmId', $umkmId)->get();

        if($product){
            return response()->json($product, 200);
        } else {
            return response()->json(['message' => 'Products not found'], 404);
        }
    }

    public function getUmkmNameByProductId($productId){
        $product = Product::select('products.id', 'umkms.name as umkm_name')
            ->join('umkms', 'products.umkmId', '=', 'umkms.id')
            ->where('products.id', $productId)
            ->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'product_id' => $product->id,
            'umkm_name' => $product->umkm_name
        ], 200);
    }


}
