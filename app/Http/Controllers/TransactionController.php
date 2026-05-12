<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Просмотр истории транзакций.
     */
    public function showHistory(Request $request): View
    {
        $transactions = $request->user()->wallet->transactions()->orderByDesc('created_at')->paginate();

        return view('transaction.history', [
            'user' => $request->user(),
            'transactions' => $transactions,
        ]);
    }
}
