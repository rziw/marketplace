<?php

namespace Tests\Feature\Http\Controllers\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use JWTAuth;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $headers;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->seed(\ShopTableSeeder::class);
        $this->seed(\ProductTableSeeder::class);
        $this->seed(\ProductShopTableSeeder::class);

        $token = $token = JWTAuth::fromUser($this->user);
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    /**
     * @test
     */
    public function storeCartSuccessfully()
    {
        $storeCartUrl = config('app.url') . '/api/cart';
        $order_data = [
            'user_id' => $this->user->id,
            'status' => 'waiting'
        ];
        $order_products_data = [
            'product_id' => 1,
            'shop_id' => 1,
            'count' => 100,
            'product_name' => 'product 1'
        ];

        $response = $this->json('POST', $storeCartUrl, array_merge($order_data, $order_products_data),
            $this->headers);

        $this->assertDatabaseHas('orders', $order_data);
        $this->assertDatabaseHas('order_products', $order_products_data);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }
}
