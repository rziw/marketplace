<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\ProductRequest;
use App\Models\Product;
use App\Models\Shop;

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
     * @param  ProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request, $shop)
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
     * @param  ProductRequest  $request
     * @param  Product $product
     * @param  Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProductRequest $request,Shop $shop, Product $product)
    {
        $product_input = $request->only(['name', 'description', 'tag']);
        $product_shop_input = $request->only(['count', 'price', 'discount', 'color', 'has_guarantee',
            'guarantee_description']);
        $product_shop_input['status'] = 'waiting';

        $product->update($product_input);
        $shop->products()->updateExistingPivot($product, $product_shop_input);

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
