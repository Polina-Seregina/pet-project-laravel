<?php

namespace Tests\Feature;

use App\Services\ExchangeRate;
use App\Models\User;
use App\Models\Wallet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery\MockInterface;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyExchangeTest extends TestCase
{
    /**
     * Внешний сервис недоступен или выбрасывает исключение.
     */
    public function test_service_is_unavailable(): void
    {
        $client = $this->partialMock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('request')->once()->andReturn(new Response(500));
        });

        $service = new ExchangeRate($client);
        try {
            $response = $service->getAmountInForeignCurrency('RUB', rand(1,100));
            $response->assertSessionHas('Сервис перевода валют недоступен.');
        } catch (Exception $e) {
        }
    }
    /**
     * Внешний API возвращает не-200 ответ
     */

    public function test_that_api_returns_not_200_response(): void
    {
        $client = $this->partialMock(Client::class, function (MockInterface $mock) {
            $mock->shouldReceive('request')->once()->andReturn(new Response(201));
        });

        $service = new ExchangeRate($client);
        try {
            $service->getAmountInForeignCurrency('RUB', 100);
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), 'Сервис перевода валют недоступен.');
        }        
    }

    /**
     * Передана невалидная или неподдерживаемая валюта
     */

    public function test_currency_is_not_valid(): void
    {
        $user = User::factory()->create();
        Wallet::factory()->create(['balance' => 100, 'user_id' => $user->id ]);
        $response = $this->actingAs($user)->post(route('wallet.currency', ['currency' => 'JPY']));
        $response->assertInvalid(['currency']);
    }
}
