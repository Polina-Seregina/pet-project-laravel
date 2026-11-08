<?php

namespace Tests\Feature;

use App\Models\Wallet;
use App\Models\User;
use App\Models\Product;
use App\Enums\TransactionType;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Проверяет, что страница с историей операций отображается.
     */
    public function test_transaction_history_showed(): void
    {
        $wallet = Wallet::factory()->create();

        $response = $this->actingAs($wallet->user)->get('/wallet/history');

        $response->assertStatus(200);
    }

    /**
     * Проверяет, что транзакция создается при пополнении кошелька.
     */
    public function test_transaction_created(): void
    {
        $wallet = Wallet::factory()->create();

        $response = $this->actingAs($wallet->user)->patch('/wallet', [
            'amount' => 100,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('transactions', [
            'wallet_id' => $wallet->id,
            'amount' => 100,
        ]);
    }

    /**
     * Проверяет, что после покупки арта появляется две транзакции.
     */

    public function test_that_there_are_two_transactions_after_the_purchase(): void
    {
        $seller = User::factory()->create();
        Wallet::factory()->create(['user_id' => $seller->id]);

        $product = Product::factory()->for($seller)->create();

        $buyer = User::factory()->create();
        Wallet::factory()->create(['user_id' => $buyer->id, 'balance' => $product->price + 1]);

        $this->actingAs($buyer)->post(route('products.buy', $product));

        $this->assertDatabaseHas('transactions', [
            'amount' => $product->price,
            'type' => TransactionType::SPENDING->value,
            'wallet_id' => $buyer->wallet->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'amount' => $product->price,
            'type' => TransactionType::REPLENISHMENT->value,
            'wallet_id' => $seller->wallet->id,
        ]);
    }
}
