<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletTopUpRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use App\Models\Transaction;

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
        $wallet->balance += $amount;

        $wallet->save();
        $request->session()->flash('status', 'Wallet top-up completed');
        
        $transaction = Transaction::create([
            'amount' => $amount,
            'type' => 'replenishment',
            'wallet_id' => $wallet->id,
        ]);

        return Redirect::route('wallet.show'); 
    }
}
