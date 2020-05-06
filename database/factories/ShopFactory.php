<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Shop;
use Faker\Generator as Faker;

$factory->define(Shop::class, function (Faker $faker) {
    return [
        'name' => 'test shop',
        'sheba_number' => '12345678',
        'status' => 'accepted'
    ];
});
