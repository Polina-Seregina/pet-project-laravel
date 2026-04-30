<?php

namespace Tests\Feature;

use App\Models\Wallet;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    /**
     * Проверяет, что страница с историей операций отображается.
     * @return void
     */
    public function test_transaction_history_showed(): void
    {
        $wallet = Wallet::factory()->create();
        
        $response = $this->actingAs($wallet->user)->get('/wallet/history');

        $response->assertStatus(200);
    }

    /**
     * Проверяет, что транзакция создается при пополнении кошелька.
     * @return void
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
}
