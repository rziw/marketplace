<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Models\Shop;
use App\Notifications\ProductChanged;
use Illuminate\Support\Facades\Mail;

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
        $shop->products()->updateExistingPivot($product, $product_shop_input);
        $this->checkForNotifyUser($shop, $shop->products($product->id)->first(), $request);

        return response()->json(['message'=> 'You have successfully updated the product.']);
    }

    private function checkForNotifyUser(Shop $shop, Product $product, UpdateProductRequest $request)
    {
        if(isset($request->status) && $product->pivot->status != $request->status) {
            Mail::fake();//Mock sending email unless all requirements of sending email are OK
            $message = "status of shop updated to $request->status.";
            $shop->user->notify(new ProductChanged($message));
        }
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
        $shop->products()->detach($product);

        return response()->json(['message'=> 'You have successfully deleted the product.']);
    }
}
