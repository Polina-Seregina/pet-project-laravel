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

    public function showHistory(Request $request): View
    {
        $transactions = $request->user()->wallet->transactions()->orderByDesc('created_at')->paginate(10);
        
        return view('transaction.history', [
            'user' => $request->user(),
            'transactions' => $transactions,
        ]);
    }
}
