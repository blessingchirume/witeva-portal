<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Codevirtus\Payments\Pesepay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/create', [UserController::class, 'create'])->name('user.create');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/make-payment', [PaymentController::class, 'seamlessPayment'])->name('make-payment');

    Route::get('/payment/result', [PaymentController::class, 'payment_result'])->name('payment-result');

    Route::get('/payment/return', [PaymentController::class, 'payment_return'])->name('payment-return');

    Route::get('/payments/error', [PaymentController::class, 'payment_error'])->name('payment-error');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/', [UserController::class, 'store'])->name('user.store');
    });

    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('item.index');
        Route::get('/create', [ItemController::class, 'create'])->name('item.create');
        Route::post('/', [ItemController::class, 'store'])->name('item.store');
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/create', [OrderController::class, 'create'])->name('order.create');
        Route::post('/', [OrderController::class, 'store'])->name('order.store');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
    });

    Route::get('/home', [App\Http\Controllers\OrderController::class, 'index'])->name('home');

});

Auth::routes();

