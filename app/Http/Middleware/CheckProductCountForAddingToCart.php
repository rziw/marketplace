<?php

namespace App\Http\Middleware;

use App\Repositories\ProductRepository;
use App\Repositories\ShopRepository;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class CheckProductCountForAddingToCart
{
    protected $shopRepository;
    protected $productRepository;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(ShopRepository $shopRepository, ProductRepository $productRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->productRepository = $productRepository;
    }

    public function handle($request, Closure $next)
    {
        $this->validate($request);

        $not_enough_quantity_message = $this->checkProductCount($request);

        if(count($not_enough_quantity_message) > 0) {
            return response()->json(['error'=> $not_enough_quantity_message]);
        }

        return $next($request);
    }

    public function checkProductCount($request)
    {
        $shop = $this->shopRepository->getWithoutRelation($request->shop_id);
        $product_with_pivot = $this->productRepository->getFromSpecificShop($shop, $request->product_id);

        return $this->compareProductInStockCountAndRequestedCount($product_with_pivot->pivot->count,
            $request->count);
    }

    private function compareProductInStockCountAndRequestedCount($product_in_stock_count, $product_requested_count)
    {
        $not_enough_quantity_message = array();

        if ($product_in_stock_count < $product_requested_count) {
            $not_enough_quantity_message[] = "only ".$product_in_stock_count." of this item is available";
        }

        return $not_enough_quantity_message;
    }

    private function validate($request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required',
            'shop_id' => 'required',
            'count' => 'required',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json(['errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
    }
}
