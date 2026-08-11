<?php

namespace App\Http\Controllers;

use App\Enums\ProductsStatus;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use App\Mail\ProductSold;
use App\Services\BuyProductService;
use App\Notifications\OrderCompleted;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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
        $priceInNewCurrency = $request['priceInNewCurrency'];
        $currency = $request['currency'];

        return view('product.show', [
            'product' => $product,
            'user' => $request->user(),
            'priceInNewCurrency' => $priceInNewCurrency,
            'currency' => $currency,
            ]);
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
        $this->authorize('update', $product);
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
        $this->authorize('update', $product);
        $user = $request->user();
        $file = $request->file('image');

        $validData = $request->validated();

        $product->fill($validData);

        if ($file) {
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
        $this->authorize('delete', $product);
        $product->delete();

        return Redirect::route('user.products.index');
    }

    /**
     * Покупка товара.
     */
    public function buyProduct(Request $request, Product $product, BuyProductService $buyProductService): RedirectResponse
    {
        $seller = $product->user;
        $buyer = $request->user();

        if ($seller->id == $buyer->id) {
            return Redirect::route('products.show', ['product' => $product])->with('status', 'Этот арт уже принадлежит тебе.');
        }

        try {
            $newProduct = $buyProductService->purchase($product, $buyer, $seller);
            $request->session()->flash('status', 'success');

        } catch (Exception $e) {
            $exception = $e->getMessage() ?: "Что-то пошло не так, попробуйте позже.";
            $request->session()->flash('status', $exception);
        }

        Mail::to($seller)->send(new ProductSold($product, $seller, $buyer));
        Notification::route('slack', env('SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify(new OrderCompleted($product, $seller, $buyer));

        return Redirect::route('products.show', ['product' => $newProduct ?? $product]);
    }
}
