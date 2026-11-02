<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Условие, когда пользователь может обновить продукт.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->id === $product->user->id;
    }

    /**
     * Когда пользователь может удалить продукт.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->id === $product->user->id;
    }
}
