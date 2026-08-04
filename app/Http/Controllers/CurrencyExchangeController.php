<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Services\ExchangeRate;
use App\Models\Product;

class CurrencyExchangeController extends Controller
{
    public function exchangeWalletBalance (Request $request):  RedirectResponse
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

    public function exchangeProductPrice (Request $request, Product $product):  RedirectResponse
    {
        $amount = $product->price;
        $currency = $request['currency'];

        try {
            $service = new ExchangeRate();
            $balanceInNewCurrency = $service->getAmountInForeignCurrency($currency, $amount);
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
            $balanceInNewCurrency = 'Сервис не доступен';
            $currency = '';
        }

        return Redirect::route('products.show', [
            'product' => $product,
            'balanceInNewCurrency' => $balanceInNewCurrency,
            'currency' => $currency,
        ]);
    }
}
