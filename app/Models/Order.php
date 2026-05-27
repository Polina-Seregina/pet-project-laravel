<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'product_id',
        'new_product_id',
        'buyer_id',
        'seller_id',
    ];

    public function soldProduct(): Product
    {
        $product = Product::withTrashed()->where('id', $this->product_id)->first();
        return $product;
    }

    public function purchasedProduct(): Product
    {
        $product = Product::withTrashed()->where('id', $this->new_product_id)->first();
        return $product;
    }

    public function buyer(): User
    {
        return User::where('id', $this->buyer_id)->first();
    }

    public function seller(): User
    {
        return User::where('id', $this->seller_id)->first();
    }
}
