<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletTopUpRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\Transaction;
use App\Enums\TransactionType;
use Exception;

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
        
        try {
            DB::transaction(function() use ($wallet, $amount) {
                $wallet->increment('balance', $amount);
                $wallet->save();

                $transaction = Transaction::create([
                    'amount' => $amount,
                    'type' => TransactionType::Replenishment->value,
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
