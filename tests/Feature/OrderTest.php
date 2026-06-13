<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Order;
use App\Models\Profile;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * Проверяет, что при попытке купить арт, создается Ордер.
     */
    public function test_order_create_when_art_is_bought(): void
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $product = Product::factory()->for($seller)->create();
        $wallet = Wallet::factory()->create(['user_id' => $buyer->id, 'balance' => $product->price + 1 ]);

        $response = $this->actingAs($buyer)->post(route('products.buy', $product));

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Списки проданных и приобреденных артов отображаются.
     */
    public function test_purchased_and_sold_products_showed(): void
    {
        $seller = User::factory()->has(Profile::factory())->create();
        $buyer  = User::factory()->has(Profile::factory())->create();

        $order = Order::factory()->create(['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);

        $response = $this->actingAs($seller)->get(route('orders.sold'));
        $response->assertStatus(200);

        $response = $this->actingAs($buyer)->get(route('orders.purchased'));
        $response->assertStatus(200);
    }
}
