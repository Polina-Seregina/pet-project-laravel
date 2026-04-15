<?php

namespace Tests\Feature\Auth;

use App\Models\{User, Profile};
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $profile = Profile::factory()->create();

        $response = $this->actingAs($profile->user)->post('/register', [
            'name' => $profile->user->name,
            'email' => $profile->user->email,
            'nickname' => $profile->nickname,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
