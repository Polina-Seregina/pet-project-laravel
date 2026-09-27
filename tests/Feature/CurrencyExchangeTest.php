<?php

namespace Tests\Feature;

use App\Services\ExchangeRate;
use App\Models\User;
use App\Models\Wallet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Exception;
use Tests\TestCase;

class CurrencyExchangeTest extends TestCase
{
    /**
     * Внешний сервис недоступен или выбрасывает исключение.
     */
    public function test_service_is_unavailable(): void
    {
        $mock = new MockHandler([
            new Response(500),
            new ConnectException('Connection refused', new Request('GET', 'test')),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ExchangeRate($client);

        $this->assertThrows(
            fn () => $service->getAmountInForeignCurrency('RUB', rand(1, 100)),
             ServerException::class
        );

        $this->assertThrows(
            fn () => $service->getAmountInForeignCurrency('RUB', rand(1, 100)),
            ConnectException::class
        );

    }
    /**
     * Внешний API возвращает не-200 ответ
     */

    public function test_that_api_returns_not_200_response(): void
    {
        $mock = new MockHandler([
            new Response(201),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $service = new ExchangeRate($client);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Сервис перевода валют недоступен.');

        $service->getAmountInForeignCurrency('RUB', 100);
        
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
