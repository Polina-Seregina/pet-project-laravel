<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class AssignRoleToUser
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->assignRole('user');
    }
}
