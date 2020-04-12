<?php

namespace App\Http\Controllers\Admin\Product;

use App\Events\Admin\ProductUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\Shop;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Shop $shop)
    {
        $products = $shop->products;
        return response()->json(compact('products'));
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
     * @param  UpdateProductRequest  $request
     * @param  Product $product
     * @param  Shop $shop
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request,Shop $shop, Product $product)
    {
        $product_input = $request->only(['name', 'description', 'tag']);
        $product_shop_input = $request->except(['name', 'description', 'tag']);

        $product->update($product_input);
        $product_with_pivot = $shop->products()->where('products.id', $product->id)->first();
        $shop->products()->updateExistingPivot($product, $product_shop_input);

        event(new ProductUpdated($product_with_pivot, $shop, $request));

        return response()->json(['message'=> 'You have successfully updated the product.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shop $shop
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($shop, Product $product)
    {
        $product->delete();
        return response()->json(['message'=> 'You have successfully deleted the product.']);
    }
}
