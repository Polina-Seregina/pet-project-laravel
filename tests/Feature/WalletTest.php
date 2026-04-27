<?php

namespace Tests\Feature;

use App\Models\User; 
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletTest extends TestCase
{
    /**
     * Проверяет, что кошелек создается при регистрации Пользователя. 
     * @return void
     */

    public function test_users_wallet_created(): void
    {
        $password = fake()->unique()->password();
        $email = fake()->unique()->email();

        $this->post('/register', [
            'name' => fake()->unique()->name(),
            'email' => $email,
            'nickname' => fake()->unique()->firstName(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $user = User::where('email', $email)->first();

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
        ]);
    }
}
