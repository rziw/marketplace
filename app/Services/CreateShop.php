<?php


namespace App\Services;


use App\Models\Shop;

class CreateShop
{
    public function store($user)
    {
        Shop::updateOrCreate(['owner_id' => $user->id]);
    }
}
