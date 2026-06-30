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
use Exception;

class ProductController extends Controller
{
    /**
     * Возвращает отображение всех продающихся товаров.
     */
    public function index(): View
    {
        return view('product.index', [
            'products' => Product::where('status', ProductsStatus::FORSALE->value)->paginate(config('app.products-on-page')),
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
            'user' => $request->user(),
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
        $path = $file->storeAs("products/{$user->id}/products", $name, 's3');

        $product = Product::create([
            'name' => $validData['name'],
            'description' => $validData['description'],
            'price' => $validData['price'],
            'author_id' => $user->id,
            'image' => $path,
            'user_id' => $user->id,
            'status' => $validData['status'],
        ]);

        return Redirect::route('products.show', ['product' => $product])->with('status', 'Арт успешно создан');
    }

    /**
     * Обновление данных у существующего товара. Обновление изображения доступно только Автору.
     */
    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $author = $product->author;
        $file = $request->file('image');

        $validData = $request->validated();
        $product->fill($validData);
       
        if (($file) && ($user == $author)) {
            $name = $file->getClientOriginalName();
            $path = $file->storeAs("products/{$user->id}/products", $name, 's3');
            $product->image = $path;
        }

        $product->save();

        return Redirect::route('products.show', ['product' => $product])->with('status', 'Арт успешно обновлен.');
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

        if ($seller->id == $buyer->id) {
            return Redirect::route('products.show', ['product' => $product])->with('status', 'Этот арт уже принадлежит тебе.');
        }

        try {
            $newProduct = DB::transaction(function () use ($product, $buyer, $seller) {

                $product = Product::where('id', $product->id)->lockForUpdate()->first();
                $sellerWallet = Wallet::where('user_id', $seller->id)->lockForUpdate()->first();
                $buyerWallet = Wallet::where('user_id', $buyer->id)->lockForUpdate()->first();

                $order = Order::create([
                    'status' => OrderStatus::CREATED->value,
                    'product_id' => $product->id,
                    'seller_id' => $seller->id,
                    'buyer_id' => $buyer->id,
                ]);

                $userHaveMoney = $product->price <= $buyerWallet->balance;
                $productIsForSale = $product->status->value === ProductsStatus::FORSALE->value;

                if (!$userHaveMoney) {
                    throw new Exception('Недостаточно средств на балансе кошелька для покупки арта.');
                }

                if (!$productIsForSale) {
                    throw new Exception('Арт не продается.');
                }

                $sellerWallet->increment('balance', $product->price);
                $sellerWallet->save();

                $buyerWallet->decrement('balance', $product->price);
                $buyerWallet->save();

                $newProduct = Product::create([
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'author_id' => $product->author_id,
                    'image' => $product->image,
                    'user_id' => $buyer->id,
                    'status' => ProductsStatus::PURCHASED->value,
                ]);

                $order->new_product_id = $newProduct->id;
                $order->status = OrderStatus::COMPLETED->value;
                $order->save();

                $product->status = ProductsStatus::SOLD->value;
                $product->save();
                $product->delete();

                Transaction::create([
                    'amount' => $product->price,
                    'type' => TransactionType::SPENDING->label(),
                    'wallet_id' => $buyerWallet->id,
                ]);

                Transaction::create([
                    'amount' => $product->price,
                    'type' => TransactionType::REPLENISHMENT->label(),
                    'wallet_id' => $sellerWallet->id,
                ]);

                return $newProduct;

            }, 3);

            $request->session()->flash('status', 'success');


        } catch (Exception $e) {
            $exception = $e->getMessage() ?: "Что-то пошло не так, попробуйте позже.";
            $request->session()->flash('status', $exception);
        }

        $product = $newProduct ?? $product;

        return Redirect::route('products.show', ['product' => $product]);
    }
}
