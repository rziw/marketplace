<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreProductRequest;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *  @param Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($shop)
    {
        $products = Product::where('shop_id', $shop->id)->get();
        return response()->json(compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Shop $shop
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request, $shop)
    {
        $input = $request->except(['shop_id']);
        $input['shop_id'] = $shop->id;

        Product::Create($input);

        return response()->json(['message'=> 'You have successfully created a product.']);
    }

    /**
     * Display the specified resource.
     *
     * @param Shop $shop
     * @param  Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($shop, Product $product)
    {
        return response()->json(compact('product'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shop $shop
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($shop, Product $product)
    {
        $product->delete();
        return response()->json(['message'=> 'You have successfully deleted the product.']);
    }
}
