<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function __invoke($name)
    {
        $products = Product::where('name', 'like', "%$name%")->get();
        return response()->json(compact('products'));
    }
}
