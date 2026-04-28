<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Wallet;
use App\Models\Transaction;


class TransactionController extends Controller
{
    /**
     * Просмотр истории транзакций.
     */

    public function show(Request $request): View
    {
        return view('transaction.history', [
            'user' => $request->user(),
            'transactions' => $request->user()->wallet->transactions,
        ]);
    }
}
