<?php

namespace App\Enums;

enum ProductsStatus: string
{
    case ForSale = 'for sale';
    case Sold = 'sold';
    case Draft = 'draft';
}
