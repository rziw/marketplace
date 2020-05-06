<?php

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::create([
            'name' => 'test_shop',
            'sheba_number' => '123456789',
            'product_type' => 'cosmetic',
            'status' => 'accepted',
            'owner_id' => 1,
            'address' => 'oljiosertjsertkaery'
        ]);
    }
}
