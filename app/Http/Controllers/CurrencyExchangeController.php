<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\ExchangeRate;
use App\Models\Product;
use App\Http\Requests\ExchangeCurrencyRequest;
use Exception;

class CurrencyExchangeController extends Controller
{
    /**
     * Создать новый экземпляр контроллера.
     */
    public function __construct(
        protected ExchangeRate $exchangeRate,
    ) {
    }

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
            $service = $this->exchangeRate;
            $balanceInNewCurrency = $service->getAmountInForeignCurrency($currency, $amount);

        } catch (Exception $e) {
            report($e);
            $request->session()->flash('status', 'Сервис перевода валют недоступен.');
            $balanceInNewCurrency = null;
            $currency = null;
        }

        return Redirect::route('wallet.show')->with([
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
            $service = $this->exchangeRate;
            $priceInNewCurrency = $service->getAmountInForeignCurrency($currency, $amount);
        } catch (Exception $e) {
            report($e);
            $request->session()->flash('status', 'Сервис перевода валют недоступен.');
            $priceInNewCurrency = null;
            $currency = null;
        }

        return Redirect::route('products.show', $product)->with([
            'priceInNewCurrency' => $priceInNewCurrency,
            'currency' => $currency,
        ]);
    }
}
