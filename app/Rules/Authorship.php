<?php

namespace App\Rules;


use Illuminate\Http\Request;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Authorship implements ValidationRule
{
    public function __construct($product, $user)
    {
        $this->user = $user;
        $this->product = $product;
    }
    
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