<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Получить список успешно выполненных заказов по продаже артов.
     */

    public function getListOfSoldProducts(Request $request): View
    {
        $user = $request->user();
        $orders = Order::where('seller_id', $user->id)
            ->where('status', OrderStatus::COMPLETED)
            ->orderByDesc('created_at')
            ->paginate(config('app.products-on-page'));
        return view('orders.sold', ['orders' => $orders]);

    }

    /**
     * Получить список приобретенных артов.
     */

    public function getListOfPurchasedProducts(Request $request): View
    {
        $orders = Order::where('buyer_id', $request->user()->id)
            ->where('status', OrderStatus::COMPLETED)
            ->orderByDesc('created_at')
            ->paginate(config('app.products-on-page'));

        return view('orders.purchased', ['orders' => $orders]);
    }

}
