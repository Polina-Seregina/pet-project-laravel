<?php

namespace App\Http\Controllers;

use App\Enums\ProductsStatus;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'products' => Product::where('status', 'for sale')->paginate(config('app.products-on-page')),
        ]);
    }

    /**
     * Возвращает отображение всех товаров, которые продает Пользователь.
     */
    public function usersIndex(Request $request): View
    {
        return view('product.myIndex', [
            'products' => $request->user()->products()->paginate(config('app.productsOnPage')),
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
        return view('product.createForm');
    }

    /**
     * Возвращает форму для редактирования товара.
     */
    public function edit(Request $request, Product $product): View
    {
        return view('product.editForm', [
            'product' => $product,
        ]);
    }

    /**
     * Публикация нового товара.
     */
    public function store(ProductRequest $request): RedirectResponse
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
            'status' => $validData['status'] === 'for sale' ?
                ProductsStatus::ForSale->value : ProductsStatus::Draft->value,
        ]);

        return Redirect::route('products.show', ['product' => $product])->with('status', 'product-created');
    }

    /**
     * Обновление данных у существующего товара.
     */
    public function update(ProductUpdateRequest $request, Product $product): RedirectResponse
    {
        $user = $request->user();
        $product->fill($request->validated());

        if ($request->hasFile('avatar')) {
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

        return Redirect::route('products.my.index');
    }
}
