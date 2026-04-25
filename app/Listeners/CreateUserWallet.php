<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
