<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;

if (App::environment('production')) {
    URL::forceScheme('https');
}
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
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('/dashboard', [ProfileController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('branches', BranchController::class);
    Route::resource('payments', PaymentMethodController::class);
    Route::get('payment-methods', [HomeController::class, 'paymentMethods'])->name('payment.methods');

    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders');
        Route::get('/fill-orders-queue', [OrderController::class, 'getOrders'])->name('orders.fill');
        Route::get('/run-queue', [OrderController::class, 'runQueue'])->name('orders.run.queue');
        Route::get('/filter', [OrderController::class, 'filter'])->name('orders.filter');
    });

    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', [LogController::class, 'index'])->name('logs');
        Route::get('/daily-orders', [LogController::class, 'dailyOrders'])->name('daily.orders');
        Route::get('/monthly-orders', [LogController::class, 'monthlyOrders'])->name('monthly.orders');
        Route::get('/show/{id}', [LogController::class, 'show'])->name('logs.show');
        
        
    });
});
