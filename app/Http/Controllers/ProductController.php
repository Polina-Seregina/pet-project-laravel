<?php

namespace App\Http\Controllers;

use App\Enums\ProductsStatus;
use App\Enums\TransactionType;
use App\Enums\OrderStatus;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Возвращает отображение всех продающихся товаров.
     */
    public function index(): View
    {
        return view('product.index', [
            'products' => Product::where('status', ProductsStatus::FORSALE->label())->paginate(config('app.products-on-page')),
        ]);
    }

    /**
     * Возвращает отображение всех товаров, которые продает Пользователь.
     */
    public function usersIndex(Request $request): View
    {
        return view('product.myIndex', [
            'products' => $request->user()->products()->paginate(config('app.products-on-page')),
        ]);
    }

    /**
     * Возвращает страницу с отображением конкретного товара.
     */
    public function show(Request $request, Product $product): View
    {
        return view('product.show', [
            'product' => $product,
            'user' => $request->user()]);
    }

    /**
     * Возвращает форму для создания товара.
     */
    public function create(): View
    {
        return view('product.createForm', ['status' => ProductsStatus::class]);
    }

    /**
     * Возвращает форму для редактирования товара.
     */
    public function edit(Request $request, Product $product): View
    {
        return view('product.editForm', [
            'product' => $product,
            'status' => ProductsStatus::class,
        ]);
    }

    /**
     * Публикация нового товара.
     */
    public function store(ProductStoreRequest $request): RedirectResponse
    {
        $validData = $request->validated();

        $user = $request->user();

        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        $path = Storage::disk('s3')->putFile("products/{$user->id}/products", $file);

        $product = Product::create([
            'name' => $validData['name'],
            'description' => $validData['description'],
            'price' => $validData['price'],
            'image' => $path,
            'user_id' => $user->id,
            'status' => $validData['status'] === ProductsStatus::FORSALE->value ?
                ProductsStatus::FORSALE->label() : ProductsStatus::DRAFT->label(),
        ]);

        return Redirect::route('products.show', ['product' => $product])->with('status', 'product-created');
    }

    /**
     * Обновление данных у существующего товара.
     */
    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $validData = $request->validated();
        $product->fill($validData);
        $product->status = $validData['status'] === ProductsStatus::FORSALE->value ?
            ProductsStatus::FORSALE->label() : ProductsStatus::DRAFT->label();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $path = Storage::disk('s3')->putFile("products/{$user->id}/products", $file);
            $product->image = $path;
        }

        $product->save();

        return Redirect::route('products.show', ['product' => $product])->with('status', 'product-updated');
    }

    /**
     * Удаление товара.
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $product->delete();

        return Redirect::route('user.products.index');
    }

    /**
     * Покупка товара.
     */
    public function buyProduct(Request $request, Product $product): RedirectResponse
    {
        $seller = $product->user;
        $buyer = $request->user();

        $userHaveMoney = $product->price <= $buyer->wallet->balance;

        if (!$userHaveMoney) {
            return Redirect::route('products.show', ['product' => $product])->with('status', 'noMoney');
        }

        $order = Order::create([
                    'status' => OrderStatus::CREATED->label(),
                    'product_id' => $product->id,
                    'seller_id' => $seller->id,
                    'buyer_id' => $buyer->id,
                ]);

        try {
            DB::transaction(function () use ($product, $buyer, $seller, &$order) {
                Wallet::where('user_id', $buyer->id)->lockForUpdate()->first();
                Wallet::where('user_id', $seller->id)->lockForUpdate()->first();

                $seller->wallet->increment('balance', $product->price);
                $seller->wallet->save();

                $buyer->wallet->decrement('balance', $product->price);
                $buyer->wallet->save();

                $newProduct = Product::create([
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                    'user_id' => $buyer->id,
                    'status' => ProductsStatus::PURCHASED->label(),
                ]);

                $order->new_product_id = $newProduct->id;
                $order->status = OrderStatus::COMPLETED->label();
                $order->save();

                $product->status = ProductsStatus::SOLD->label();
                $product->save();
                $product->delete();

                $transactionBuyer = Transaction::create([
                    'amount' => $product->price,
                    'type' => TransactionType::SPENDING->label(),
                    'wallet_id' => $buyer->wallet->id,
                ]);

                $transactionSeller = Transaction::create([
                    'amount' => $product->price,
                    'type' => TransactionType::REPLENISHMENT->label(),
                    'wallet_id' => $seller->wallet->id,
                ]);

            }, 3);

            $request->session()->flash('status', 'success');

        } catch (Exception $e) {

            $request->session()->flash('status', 'fail');
            $order->new_product_id = $newProduct->id;
            $order->status = OrderStatus::CENCELED->label();
            $order->save();
        }

        return Redirect::route('user.products.index');
    }
}
