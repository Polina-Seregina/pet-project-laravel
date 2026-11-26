<?php

namespace Tests\Feature;

use App\Services\ExchangeRate;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Product;
use App\Enums\CurrencyEnum;
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
    private function mockExchangeRate(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['conversion_rates' => ['RUB' => 90.0]])),
            new Response(200, [], json_encode(['conversion_rates' => ['RUB' => 90.0]])),
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $this->app->instance(ExchangeRate::class, new ExchangeRate($client));
    }

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
            fn () => $service->getAmountInForeignCurrency('RUB', 100),
            ServerException::class
        );

        $this->assertThrows(
            fn () => $service->getAmountInForeignCurrency('RUB', 100),
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

    /**
     * Проверяет, что конвертация валюты происходит успешно.
     */

    public function test_that_currency_exchange_successful(): void
    {
        $this->mockExchangeRate();

        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 11]);
        Wallet::factory()->create(['user_id' => $user->id, 'balance' => 11]);

        $this->actingAs($user)->post(route('products.currency', ['product' => $product, 'currency' => CurrencyEnum::RUB->value]));

        $response = $this->get(route('products.show', $product));
        $response->assertViewHas('priceInNewCurrency', 990.0);
        $response->assertViewHas('currency', CurrencyEnum::RUB->value);

        $this->actingAs($user)->post(route('wallet.currency', ['currency' => CurrencyEnum::RUB->value]));

        $response = $this->get(route('wallet.show'));
        $response->assertViewHas('balanceInNewCurrency', 990.0);
        $response->assertViewHas('currency', CurrencyEnum::RUB->value);

    }
}
