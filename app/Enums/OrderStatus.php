<?php

namespace App\Enums;

enum OrderStatus: string
{
    case CREATED = 'created';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Создан',
            self::COMPLETED => 'Выполнен',
        };
    }
}
