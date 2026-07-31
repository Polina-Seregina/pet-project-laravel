<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\WalletTopUpRequest;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\ExchangeRate;

class WalletController extends Controller
{
    /**
     * Просмотр страницы кошелька .
     */
    public function show(Request $request): View
    {
        $wallet = $request->user()->wallet;

        return view('wallet.show', [
            'user' => $request->user(),
            'wallet' => $wallet,
            'balanceInNewCurrency' => $balanceInNewCurrency ?? 'запроса пока не было',
            'currency' => $currency ?? '',
        ]);
    }

    /**
     * Просмотр страницы кошелька .
     */
    public function currency(Request $request): View
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

        return view('currency.exchangeWindow', [
            'user' => $request->user(),
            'wallet' => $wallet,
            'balanceInNewCurrency' => $balanceInNewCurrency,
            'currency' => $currency,
        ]);

    }

    /**
     * Просмотр формы пополнения кошелька.
     */
    public function showTopUpForm(Request $request): View
    {
        return view('wallet.top-up-balance-form', [
            'user' => $request->user(),
            'wallet' => $request->user()->wallet,
        ]);
    }

    /**
     * Пополнение баланса кошелька с flash сообщением об успещшости.
     */
    public function topUp(WalletTopUpRequest $request): RedirectResponse
    {
        $validData = $request->validated();
        $amount = $validData['amount'];
        $wallet = $request->user()->wallet;

        try {
            DB::transaction(function () use ($wallet, $amount) {
                $wallet->increment('balance', $amount);
                $wallet->save();

                $transaction = Transaction::create([
                    'amount' => $amount,
                    'type' => TransactionType::REPLENISHMENT->value,
                    'wallet_id' => $wallet->id,
                ]);
            }, 3);
            $request->session()->flash('status', 'success');
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
        }

        return Redirect::route('wallet.show');
    }
}
