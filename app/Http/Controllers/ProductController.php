<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Enums\ProductsStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Возвращает отображение всех продающихся товаров.
     */
    public function index(): View
    {
        return view('product.index', [
            'products' => Product::where('status', 'for sale')->paginate(config('app.productsOnPage')),
        ]);
    }
    /**
     * Возвращает отображение всех товаров, которые продает Пользователь.
     */
    public function usersIndex(Request $request): View
    {
        return view('product.myIndex', [
            'products' => $request->user()->products()->paginate(config('app.productsOnPage'))
        ]);
    }
    /**
     * Возвращает страницу с отображением конкретного товара.
     */
    public function show(Request $request, $productId): View
    {
        return view('product.show', [
            'product' => Product::findOrFail($productId),
            'user' => $request->user()]);
    }

    /**
     * Возвращает форму для создания товара.
     */
    public function create(): View
    {
        return view('product.createForm');
    }

    /**
     * Возвращает форму для редактирования товара.
     */
    public function edit(Request $request, $productId): View
    {
        $product = $productId ? Product::findOrFail($productId) : null;

        return view('product.editForm', [
            'product' => $product,
            'user' => $request->user(),
        ]);
    }
    /**
     * Публикация нового товара.
     */
    public function publish(ProductRequest $request): RedirectResponse
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
            'status' => ProductsStatus::ForSale->value,
        ]);

        return Redirect::route('product.show', ['productId' => $product->id])->with('status', 'product-created');
    }
    /**
     * Обновление данных у существующего товара.
     */
    public function update(ProductUpdateRequest $request, $productId): RedirectResponse
    {
        $user = $request->user();

        $product = Product::findOrFail($productId);
        $product->fill($request->validated());

        if ($request->hasFile('avatar')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $path = Storage::disk('s3')->putFile("products/{$user->id}/products", $file);
            $product->image = $path;
        }

        $product->save();

        return Redirect::route('product.show', ['productId' => $productId])->with('status', 'product-updated');
    }
    /**
     * Удаление товара.
     */
    public function destroy(Request $request, $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return Redirect::route('product.my.index');
    }
}
