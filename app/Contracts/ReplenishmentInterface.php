<?php

namespace App\Contracts;

interface ReplenishmentInterface
{
    public function topUp($wallet, $amount);
} 
