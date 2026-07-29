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
use App\Enums\CurrencyEnum;

class WalletController extends Controller
{
    /**
     * Просмотр страницы кошелька .
     */
    public function show(Request $request): View
    {
        $currency = $request['currency'] ?? CurrencyEnum::RUB->value;
        $wallet = $request->user()->wallet;
        $service = new ExchangeRate();
        try {
            $rate = $service->getRate($currency);
            $balanceInNewCurrency = round($wallet->balance * $rate, 2);
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
            $balanceInNewCurrency = 'Сервис не доступен';
            $currency = '';
        }

        return view('wallet.show', [
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
            $request->session()->flash('status', 'Wallet top-up completed');
        } catch (Exception $e) {
            $request->session()->flash('status', 'Replenishment failed');
        }

        return Redirect::route('wallet.show');
    }
}
