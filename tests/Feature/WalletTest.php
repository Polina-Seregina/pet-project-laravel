<?php

namespace Tests\Feature;

use App\Models\Wallet;
use Tests\TestCase;
use App\Http\Controllers\WalletController;
use App\Services\SimpleTopUpService;
use Illuminate\Support\Facades\Auth;

class WalletTest extends TestCase
{
    /**
     * Проверяет, что кошелек создается при регистрации Пользователя и имеет нулевой баланс.
     * @return void
     */

    public function test_users_wallet_created(): void
    {
        $password = fake()->unique()->password(8, 20);
        $email = fake()->unique()->email();

        $response = $this->post('/register', [
            'name' => fake()->unique()->name(),
            'email' => $email,
            'nickname' => fake()->unique()->firstName(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $user = Auth::user();

        $this->assertDatabaseHas('wallets', [
            'user_id' => $user->id,
        ]);

        $this->assertEquals(0, $user->wallet->balance);
    }

    /**
     * Проверяет, что страница кошелька успешно отображается.
     * @return void
     */

    public function test_wallet_page_showed(): void
    {
        $wallet = Wallet::factory()->create();

        $response = $this->actingAs($wallet->user)->get('/wallet');

        $response->assertViewIs('wallet.show');

        $response->assertStatus(200);
    }

    /**
     * Проверка на отображение формы пополнения.
     * @return void
     */

    public function test_wallet_replenishment_form_showed(): void
    {
        $wallet = Wallet::factory()->create();

        $response = $this->actingAs($wallet->user)->get('/wallet/replenishment');

        $response->assertStatus(200);

        $response->assertViewIs('wallet.top-up-balance-form');
    }

    /**
     * Проверяет, что баланс кошелька увеличивается на сумму, введенную в форме для пополнения.
     * @return void
     */

    public function test_increasing_balance_upon_replenishment(): void
    {
        $wallet = Wallet::factory()->create();

        $response = $this->actingAs($wallet->user)->patch('/wallet', [
            'amount' => 100,
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('wallets', [
            'user_id' => $wallet->user->id,
            'balance' => 100,
        ]);
    }

    /**
     * Проверяет, что при переходе на /wallet без авторизации происходит переадресация на /login;
     */

    public function test_get_wallet_without_user(): void
    {
        $response = $this->get('/wallet');

        $response->assertRedirect('/login');

    }

    /**
     * Проверка, что при создании WalletController в replenishmentService приходит необходимый сервис.
     */

    public function test_that_binding_is_working(): void
    {
        $walletController = resolve(WalletController::class);
        $this->assertInstanceOf(SimpleTopUpService::class, $walletController->replenishmentService);
    }
}
