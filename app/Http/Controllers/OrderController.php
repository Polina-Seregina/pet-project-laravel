<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Получить успешно выполненные заказы по продаже/покупке артов.
     */

    private function getOrders(String $role, String $nameOfView, Request $request)
    {
        $orders = Order::with(['seller', 'buyer', 'soldProduct', 'purchasedProduct' ])
            ->where($role, $request->user()->id)
            ->where('status', OrderStatus::COMPLETED->value)
            ->orderByDesc('created_at')
            ->paginate(config('app.products-on-page'));

        return view($nameOfView, ['orders' => $orders]);
    }
    /**
     * Получить список успешно выполненных заказов по продаже артов.
     */

    public function getListOfSoldProducts(Request $request): View
    {
        return $this->getOrders('seller_id', 'orders.sold', $request);
    }

    /**
     * Получить успешно выполненные заказы по покупке артов.
     */

    public function getListOfPurchasedProducts(Request $request): View
    {
        return $this->getOrders('buyer_id', 'orders.purchased', $request);

    }

}
