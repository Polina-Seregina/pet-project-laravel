<?php

namespace App\Services;

use App\Contracts\ReplenishmentInterface;
use App\Models\Transaction;
use App\Enums\TransactionType;
use Illuminate\Support\Facades\DB;

class SimpleTopUpService implements ReplenishmentInterface
{
    public function topUp($wallet, $amount): Void
    {
        DB::transaction(function () use ($wallet, $amount) {
            $wallet->increment('balance', $amount);

            Transaction::create([
                'amount' => $amount,
                'type' => TransactionType::REPLENISHMENT->value,
                'wallet_id' => $wallet->id,
            ]);
        }, 3);
    }
}
