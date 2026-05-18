<?php

namespace App\Enums;

enum ProductsStatus: string
{
    case FORSALE = 'for sale';
    case SOLD = 'sold';
    case DRAFT = 'draft';

    public function label(): string
    {
        return match($this) {
            self::FORSALE => 'The art is for sale.',
            self::SOLD => 'The art was sold.',
            self::DRAFT => 'The art is a draft.',
        };
    }

}
