<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;

#[Fillable([
    'status',
    'product_id',
    'new_product_id',
    'buyer_id',
    'seller_id',
])]

class Order extends Model
{
    use HasFactory;

    public function soldProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function purchasedProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'new_product_id')->withTrashed();
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class
        ];
    }
}
