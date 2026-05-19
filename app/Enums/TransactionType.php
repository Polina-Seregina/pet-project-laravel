<?php

namespace App\Enums;

enum TransactionType: string
{
    case REPLENISHMENT = 'replenishment';
    case SPENDING = 'write-off';

    public function label()
    {
        return match ($this) {
            self::REPLENISHMENT => 'Пополнение',
            self::SPENDING => 'Списание',
        };
    }
}
