<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return view('login');
});

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('registerFunction', [UserController::class, 'registerFunction'])->name('registerFunction');

Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('loginFunction', [UserController::class, 'loginFunction'])->name('loginFunction');

Route::get('index', [UserController::class, 'roleChecker'])->name('index');
Route::get('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('index', [ProductController::class, 'indexAdmin'])->name('index');

    Route::resource('products', ProductController::class);
    Route::resource('genres', GenreController::class);

    Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions.index');

    Route::get('/transactions/{id}/receipt',
        [TransactionController::class, 'printReceipt']
    )->name('transactions.receipt');
});

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('index', [ProductController::class, 'index'])->name('index');

    Route::get('cart', [CartController::class, 'index'])->name('cart');
    Route::get('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

    Route::get('inventory', [UserController::class, 'inventory'])->name('inventory');

    Route::get('/transactions/summary/pdf',
    [TransactionController::class, 'printAllTransactionsPdf']
    )->name('transactions.summary-pdf');
});