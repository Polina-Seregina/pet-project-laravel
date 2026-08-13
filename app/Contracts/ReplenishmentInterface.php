<?php

namespace App\Contracts;

use App\Models\Wallet;

interface ReplenishmentInterface
{
    public function topUp(Wallet $wallet, $amount);
}