<?php

namespace App\Services;

use App\Repositories\ShopRepository;

class ShopDataProviderService
{
    private $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function getShopProduct(int $shopId, int $productId)
    {
        $shop = $this->shopRepository->find($shopId);
        $product = $shop->products()->whereIn('product_id', [$productId])->firstOrfail();

        return $product;
    }
}
