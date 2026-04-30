<?php

namespace App\Enums;

enum TransactionType: string
{
    case Replenishment = 'replenishment';
    case Spending = 'write-off';
}

