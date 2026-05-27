<?php

namespace App\Enums;

enum OrderStatus: string
{
    case CREATED = 'created';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::CREATED => 'Создан',
            self::CANCELED => 'Отменен',
            self::COMPLETED => 'Выполнен',
        };
    }
}