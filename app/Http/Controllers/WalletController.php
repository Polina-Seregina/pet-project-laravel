<?php

namespace App\Http\Controllers;

use App\Contracts\ReplenishmentInterface;
use App\Http\Requests\WalletTopUpRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Просмотр страницы кошелька.
     */
    public function show(Request $request): View
    {
        $wallet = $request->user()->wallet;
        $balanceInNewCurrency = $request['balanceInNewCurrency'];
        $currency = $request['currency'];

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
    public function edit(Request $request): View
    {
        return view('wallet.top-up-balance-form', [
            'user' => $request->user(),
            'wallet' => $request->user()->wallet,
        ]);
    }

    /**
     * Пополнение баланса кошелька с flash сообщением об успещшости.
     */
    public function update(WalletTopUpRequest $request, ReplenishmentInterface $replenishmentInterface): RedirectResponse
    {
        $validData = $request->validated();
        $amount = $validData['amount'];
        $wallet = $request->user()->wallet;

        try {
            $replenishmentInterface->topUp($wallet, $amount);
            $request->session()->flash('status', 'success');
        } catch (Exception $e) {
            $request->session()->flash('status', $e->getMessage());
        }

        return Redirect::route('wallet.show');
    }
}
