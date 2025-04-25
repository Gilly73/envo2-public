<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuoteCouchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProcessOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customer/create', [CustomerController::class, 'store']);
        Route::post('/quote/couch', [QuoteCouchController::class, 'getQuote']);
        Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent']);
        Route::post('/payment/refund', [PaymentController::class, 'refundPayment']);
        Route::post('/order/process', [ProcessOrderController::class, 'process']);
        Route::post('/order/create', [ProcessOrderController::class, 'create']);
    });
});
