<?php

use App\Models\Shop;
use Illuminate\Database\Seeder;

class ProductShopTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shop = Shop::find(1);

        $shop->products()->attach([1 =>
            [
                'count' => 100,
                'price' => 100,
                'color' => 'blue',
                'has_guarantee' => 1
            ]
        ]);
    }
}
