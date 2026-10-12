<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Enums\ProductsStatus;
use App\Models\Wallet;
use App\Models\Product;
use App\Services\BuyProductService;
use TypeError;
use Exception;

class BuyProductTest extends TestCase
{
    /**
     * Одновременная покупка одного товара двумя покупателями.
     */

    public function test_synchronous_purchase_of_single_product_by_two_buyers(): void
    {
        $seller = User::factory()->create();
        $buyerOne = User::factory()->create();
        $buyerTwo = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $seller->id]);
        $productForOne = Product::find($product->id);
        $productForTwo = Product::find($product->id);

        $sellerWallet = Wallet::factory()->create(['user_id' => $seller->id]);
        Wallet::factory()->create(['user_id' => $buyerOne->id, 'balance' => $product->price + 100]);
        $buyerTwoWallet = Wallet::factory()->create(['user_id' => $buyerTwo->id, 'balance' => $product->price + 100]);

        $service = new BuyProductService();
        $service->purchase($productForOne, $buyerOne, $seller);

        $this->assertThrows(
            fn () => $service->purchase($productForTwo, $buyerTwo, $seller),
            TypeError::class
        );

        $this->assertDatabaseCount('orders', 1);

        $this->assertDatabaseMissing('products', [
            'user_id' => $buyerTwo->id,
            'status' => ProductsStatus::PURCHASED->value,
        ]);

        $this->assertEquals($buyerTwoWallet->refresh()->balance, $product->price + 100);
    }

    /**
     * Покупка продукта, если у покупателя/продавца нет кошелька.
     */

    public function test_buy_product_without_wallets(): void
    {
        $seller = User::factory()->has(Wallet::factory())->create();
        $buyer = User::factory()->create();
        $product = Product::factory()->create(['user_id' => $seller->id]);

        $this->assertNull($buyer->wallet);

        $service = new BuyProductService();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Кошелёк покупателя не найден.');

        $service->purchase($product, $buyer, $seller);
    }
}
