<?php

namespace App\Enums;

enum ProductsStatus: string
{
    case FORSALE = 'for_sale';
    case SOLD = 'sold';
    case DRAFT = 'draft';
    case PURCHASED = 'purchased';

    public function label(): string
    {
        return match ($this) {
            self::FORSALE => 'На продажу',
            self::SOLD => 'Продан',
            self::DRAFT => 'Черновик',
            self::PURCHASED => 'Приобретен'
        };
    }
}
