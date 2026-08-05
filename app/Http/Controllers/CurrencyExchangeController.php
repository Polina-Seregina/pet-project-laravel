<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\ExchangeRate;
use App\Models\Product;

class CurrencyExchangeController extends Controller
{
    /**
     * Метод для перевода суммы на балансе кошелька из USD в выбранную валюту - CNY, RUB, EUR.
     */

    public function exchangeWalletBalance(Request $request): RedirectResponse
    {
        $wallet = $request->user()->wallet;
        $amount = $wallet->balance;
        $currency = $request['currency'];

        try {
            $service = new ExchangeRate();
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

    public function exchangeProductPrice(Request $request, Product $product): RedirectResponse
    {
        $amount = $product->price;
        $currency = $request['currency'];

        try {
            $service = new ExchangeRate();
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
