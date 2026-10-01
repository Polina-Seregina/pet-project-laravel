<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
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
        Wallet::factory()->create(['user_id' => $buyer->id, 'balance' => $product->price + 1 ]);
        Wallet::factory()->create(['user_id' => $seller->id]);

        $this->actingAs($buyer)->post(route('products.buy', $product));

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'product_id' => $product->id,
            'status' => OrderStatus::COMPLETED->value,
        ]);
    }

    /**
     * Списки проданных и приобреденных артов отображаются.
     */
    public function test_purchased_and_sold_products_showed(): void
    {
        $seller = User::factory()->has(Profile::factory())->create();
        $buyer  = User::factory()->has(Profile::factory())->create();

        Order::factory()->create(['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);

        $response = $this->actingAs($seller)->get(route('orders.sold'));
        $response->assertStatus(200);

        $response = $this->actingAs($buyer)->get(route('orders.purchased'));
        $response->assertStatus(200);
    }

    /**
     * В списке заказов(sold) не отображаются незавершённые заказы.
     */
    public function test_incomplete_orders_are_not_displayed_in_orders_sold(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.sold'));
        $this->assertEquals(0, $response['orders']->toArray()['total']);

        Order::factory()->create(['status' => OrderStatus::CREATED->value, 'seller_id' => $user->id]);
        Order::factory()->create(['seller_id' => $user->id]);
        Order::factory()->create(['seller_id' => $user->id]);
        
        $response = $this->actingAs($user)->get(route('orders.sold'));
        $this->assertEquals(2, $response['orders']->toArray()['total']);

        Order::factory()->create(['status' => OrderStatus::CREATED->value, 'seller_id' => $user->id]);
        
        $response = $this->actingAs($user)->get(route('orders.sold'));
        $this->assertEquals(2, $response['orders']->toArray()['total']);
    }

    /**
     *  списке заказов(purchased) не отображаются незавершённые заказы.
     */
    public function test_incomplete_orders_are_not_displayed_in_orders_purchased(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.purchased'));
        $this->assertEquals(0, $response['orders']->toArray()['total']);

        Order::factory()->create(['status' => OrderStatus::CREATED->value, 'buyer_id' => $user->id]);
        Order::factory()->create(['buyer_id' => $user->id]);
        Order::factory()->create(['buyer_id' => $user->id]);
        
        $response = $this->actingAs($user)->get(route('orders.purchased'));
        $this->assertEquals(2, $response['orders']->toArray()['total']);

        Order::factory()->create(['status' => OrderStatus::CREATED->value, 'buyer_id' => $user->id]);
        
        $response = $this->actingAs($user)->get(route('orders.purchased'));
        $this->assertEquals(2, $response['orders']->toArray()['total']);
    }

    /**
     * Проверяет, что чужие заказы не отображаются в списке заказов пользователя.
     */
    public function test_other_people_s_orders_are_not_displayed(): void
    {
        $user = User::factory()->create();

        $anotherBuyer = User::factory()->create();
        $anotherSeller = User::factory()->create();

        Order::factory()->create(['buyer_id' => $anotherBuyer->id, 'seller_id' => $anotherSeller->id]);
        Order::factory()->create(['buyer_id' => $anotherBuyer->id, 'seller_id' => $anotherSeller->id]);
        Order::factory()->create(['buyer_id' => $anotherBuyer->id, 'seller_id' => $anotherSeller->id]);

        $response = $this->actingAs($user)->get(route('orders.purchased'));
        $this->assertEquals(0, $response['orders']->toArray()['total']);

        $response = $this->actingAs($user)->get(route('orders.sold'));
        $this->assertEquals(0, $response['orders']->toArray()['total']);

        Order::factory()->create(['buyer_id' => $user->id, 'seller_id' => $anotherSeller->id]);
        Order::factory()->create(['buyer_id' => $anotherBuyer->id, 'seller_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('orders.sold'));
        $this->assertEquals(1, $response['orders']->toArray()['total']);

        $response = $this->actingAs($user)->get(route('orders.purchased'));
        $this->assertEquals(1, $response['orders']->toArray()['total']);
    }
}
