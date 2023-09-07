<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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


Route::get('/payment', function () {
    return view('payment');
});

Route::get('/payment-success', [PaymentController::class, 'paymentSuccess']);

Route::get('/momo-pay', [PaymentController::class, 'momoPay'])->name('payWithMomo');

Route::get('/momo-atm-pay', [PaymentController::class, 'momoATMPay'])->name('payAtmMomo');
