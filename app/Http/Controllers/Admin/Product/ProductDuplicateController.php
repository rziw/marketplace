<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ProductDuplicateController extends Controller
{
    public function __invoke(Request $request, Shop $shop, Product $product)
    {
        $input['product_id'] =  $request->product_id;
        $input['extra_description'] = $product->description;

        $shop->products()->updateExistingPivot($product, $input);
        $product->delete();

        return response()->json(['message'=> 'You have successfully updated the product.']);
    }
}
