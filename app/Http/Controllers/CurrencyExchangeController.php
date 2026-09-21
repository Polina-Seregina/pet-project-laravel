<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\ExchangeRate;
use App\Models\Product;
use GuzzleHttp\Client;
use App\Http\Requests\ExchangeCurrencyRequest;
use Exception;

class CurrencyExchangeController extends Controller
{
    /**
     * Метод для перевода суммы на балансе кошелька из USD в выбранную валюту - CNY, RUB, EUR.
     */

    public function exchangeWalletBalance(ExchangeCurrencyRequest $request): RedirectResponse
    {
        $wallet = $request->user()->wallet;
        $amount = $wallet->balance;

        $validData = $request->validated();
        $currency = $validData['currency'];

        try {
            $client = new Client([
                'base_uri' => config('services.currate.base-url'),
                'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
            ]);
            $service = new ExchangeRate($client);
            $balanceInNewCurrency = $service->getAmountInForeignCurrency($currency, $amount);
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
            $balanceInNewCurrency = 'Сервис не доступен';
            $currency = '';
        }

        return Redirect::route('wallet.show', [
            'balanceInNewCurrency' => $balanceInNewCurrency,
            'currency' => $currency,
        ]);
    }

    /**
     * Метод для перевода стоимости Арта из USD в выбранную валюту - CNY, RUB, EUR.
     */

    public function exchangeProductPrice(ExchangeCurrencyRequest $request, Product $product): RedirectResponse
    {
        $validData = $request->validated();
        $currency = $validData['currency'];

        $amount = $product->price;

        try {
            $client = new Client([
                'base_uri' => config('services.currate.base-url'),
                'curl' => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4],
            ]);
            $service = new ExchangeRate($client);
            $priceInNewCurrency = $service->getAmountInForeignCurrency($currency, $amount);
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
            $priceInNewCurrency = 'Сервис не доступен';
            $currency = '';
        }

        return Redirect::route('products.show', [
            'product' => $product,
            'priceInNewCurrency' => $priceInNewCurrency,
            'currency' => $currency,
        ]);
    }
}
