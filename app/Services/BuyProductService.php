<?php

namespace App\Services;

use App\Enums\ProductsStatus;
use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class BuyProductService
{
    public function purchase(Product $product, User $buyer, User $seller): Product
    {
        $newProduct = DB::transaction(function () use ($product, $buyer, $seller) {

            $product = Product::where('id', $product->id)->lockForUpdate()->first();
            $sellerWallet = Wallet::where('user_id', $seller->id)->lockForUpdate()->first();
            $buyerWallet = Wallet::where('user_id', $buyer->id)->lockForUpdate()->first();

            $order = $this->createOrder($product, $seller, $buyer);

            $this->checkThatBuyerHaveMoney($product, $buyerWallet);
            $this->checkProductStatus($product);

            $this->writeOffMoney($buyerWallet, $product->price);
            $this->topUpWallet($sellerWallet, $product->price);

            $newProduct = $this->createNewProduct($product, $buyer);

            $this->updateOrder($order, $newProduct);

            $this->updateAndDeleteOldProduct($product);

            $this->createWalletHistory($buyerWallet, $sellerWallet, $product->price);
            
            return $newProduct;

        }, 3);

        return $newProduct;
    }

    public function checkThatSellerIsNotBuyer(User $seller, User $buyer)
    {
        if ($seller->id == $buyer->id) {
            return Redirect::route('products.show', ['product' => $product])->with('status', 'Этот арт уже принадлежит тебе.');
        }
    }

    private function writeOffMoney(Wallet $wallet, $amount): void
    {
        $wallet->decrement('balance', $amount);
        $wallet->save();
    }

    private function topUpWallet(Wallet $wallet, $amount): void
    {
        $wallet->increment('balance', $amount);
        $wallet->save();
    }

    private function createWalletHistory(Wallet $buyerWallet, Wallet $sellerWallet, $amount): void
    {
        Transaction::create([
            'amount' => $amount,
            'type' => TransactionType::SPENDING->value,
            'wallet_id' => $buyerWallet->id,
        ]);

        Transaction::create([
            'amount' => $amount,
            'type' => TransactionType::REPLENISHMENT->value,
            'wallet_id' => $sellerWallet->id,
        ]);
    }

    private function checkThatBuyerHaveMoney(Product $product, Wallet $buyerWallet)
    {
        if (!($product->price <= $buyerWallet->balance)) {
            throw new Exception('Недостаточно средств на балансе кошелька для покупки арта.');
        }
    }

    private function checkProductStatus(Product $product)
    {
        if (!($product->status->value === ProductsStatus::FORSALE->value)) {
            throw new Exception('Арт не продается.');
        }
    }

    private function createOrder(Product $product, User $seller,  User $buyer): Order
    {
        return Order::create([
                    'status' => OrderStatus::CREATED->value,
                    'product_id' => $product->id,
                    'seller_id' => $seller->id,
                    'buyer_id' => $buyer->id,
                ]);
    }

    private function updateOrder(Order $order, Product $product): void
    {
        $order->new_product_id = $product->id;
        $order->status = OrderStatus::COMPLETED->value;
        $order->save();
    }

    private function updateAndDeleteOldProduct(Product $product): void
    {
        $product->status = ProductsStatus::SOLD->value;
        $product->save();
        $product->delete();
    }

    private function createNewProduct(Product $product, User $buyer): Product
    {
        return Product::create([
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'image' => $product->image,
            'user_id' => $buyer->id,
            'status' => ProductsStatus::PURCHASED->value,
        ]);
    }
}