<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'amount',
    'type',
    'wallet_id'
])]

class Transaction extends Model
{
    use HasFactory;

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class
        ];
    }
}
