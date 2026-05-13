<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
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
    Route::get('/wallet/replenishment', [WalletController::class, 'showTopUpForm'])->name('wallet.replenishment.form');
    Route::patch('/wallet', [WalletController::class, 'topUp'])->name('wallet.replenishment');
    Route::get('/wallet/history', [TransactionController::class, 'showHistory'])->name('transaction.history');

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('/my/products', [ProductController::class, 'usersIndex'])->name('product.my.index');
    Route::get('/products/{productId}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/products/{productId}/edit', [ProductController::class, 'edit'])->name('product.edit')->middleware('owner');
    Route::post('/products', [ProductController::class, 'publish'])->name('product.publish');
    Route::patch('/products/{productId}', [ProductController::class, 'update'])->name('product.update')->middleware('owner');
    Route::delete('/products/{productId}', [ProductController::class, 'destroy'])->name('product.destroy')->middleware('owner');
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__.'/auth.php';
