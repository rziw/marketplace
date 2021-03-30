<?php

namespace App\Services\Order;

use Exception;
use App\Repositories\ShopRepository;
use App\Http\Requests\User\CartRequest;
use App\Repositories\ProductRepository;

class CheckProductCountForAddingToCartGuard
{
    protected $shopRepository;
    protected $productRepository;

    public function __construct(ShopRepository $shopRepository, ProductRepository $productRepository)
    {
        $this->shopRepository = $shopRepository;
        $this->productRepository = $productRepository;
    }

    public function handle($request)
    {
        $notEnoughQuantityMessage = $this->checkProductCount($request);

        if(count($notEnoughQuantityMessage) > 0) {
            throw new Exception($notEnoughQuantityMessage, 422);
        }
    }

    public function checkProductCount(CartRequest $request)
    {
        $shop = $this->shopRepository->find($request->shop_id);
        $productWithPivot = $this->productRepository->getFromSpecificShop($shop, $request->product_id);

        return $this->compareProductInStockCountAndRequestedCount(
            $productWithPivot->pivot->count,
            $request->count
        );
    }

    private function compareProductInStockCountAndRequestedCount($product_in_stock_count, $product_requested_count)
    {
        $notEnoughQuantityMessage = array();

        if ($product_in_stock_count < $product_requested_count) {
            $notEnoughQuantityMessage[] = "only ".$product_in_stock_count." of this item is available";
        }

        return $notEnoughQuantityMessage;
    }
}
