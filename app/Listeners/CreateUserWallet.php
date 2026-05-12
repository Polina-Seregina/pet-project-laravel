<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class CreateUserWallet
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->wallet()->firstOrCreate(['balance' => 0]);
    }
}
