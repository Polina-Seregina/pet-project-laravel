<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Service\ExchangeRate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet', [WalletController::class, 'show'])->name('wallet.currency');
    Route::get('/wallet/replenishment', [WalletController::class, 'showTopUpForm'])->name('wallet.replenishment.form');
    Route::patch('/wallet', [WalletController::class, 'topUp'])->name('wallet.replenishment');
    Route::get('/wallet/history', [TransactionController::class, 'showHistory'])->name('transaction.history');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/my/products', [ProductController::class, 'usersIndex'])->name('user.products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('onlyPublic');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/buy', [ProductController::class, 'buyProduct'])->name('products.buy')->middleware('onlyPublic');

    Route::get('/orders/sold', [OrderController::class, 'getListOfSoldProducts'])->name('orders.sold');
    Route::get('/orders/purchased', [OrderController::class, 'getListOfPurchasedProducts'])->name('orders.purchased');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/admin/{user}', [AdminController::class, 'show'])->name('admin.show');
        Route::patch('/admin/{user}', [AdminController::class, 'update'])->name('admin.update');
    });

});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
