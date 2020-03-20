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
        $product_input = $request->only(['name', 'description', 'tag']);
        $product_shop_input = $request->only(['count', 'price', 'discount', 'color', 'has_guarantee',
            'guarantee_description']);

        $product = Product::Create($product_input);
        $shop->products()->attach([$product->id => $product_shop_input]);

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
     * @param  Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->except(['shop_id', 'status']);
        $input['status'] = 'waiting';

        $product->update($input);

        return response()->json(['message'=> 'You have successfully updated the product.']);
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
