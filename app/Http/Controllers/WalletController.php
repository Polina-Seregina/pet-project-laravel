<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Просмотр страницы кошелька .
     */

    public function show(Request $request): View
    {
        return view('wallet.show', [
            'user' => $request->user(),
            'wallet' => $request->user()->wallet,
        ]);
    }

    /**
     * Просмотр формы пополнения кошелька.
     */

    public function show_top_up_balance(Request $request): View
    {
        return view('wallet.top-up-balance-form', [
            'user' => $request->user(),
            'wallet' => $request->user()->wallet,
        ]);
    }
    
    /**
     * Пополнение баланса кошелька с flash сообщением об успещшости.
     */

    public function top_up_balance(WalletTopUpRequest $request): RedirectResponse
    {
        $amount = $request->validated();

    }
}
