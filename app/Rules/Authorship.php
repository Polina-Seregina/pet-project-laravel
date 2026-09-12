<?php

namespace App\Rules;

use Closure;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class Authorship implements ValidationRule
{
    public function __construct(
        private Product $product, 
        private User $user )
        {}

    /**
     * Правило проверки.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->user->is($this->product->author)) {
            $fail('Изображение может менять только автор.');
        }
    }
}
