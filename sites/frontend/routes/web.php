<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouchController;

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

// Testing For Postman
Route::get('/csrf-token', function() {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/test', [TestController::class, 'index'])->name('test.index');
Route::post('/test', [TestController::class, 'store'])->name('test.store');



// Application
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/customer-details', function () {
    return Inertia::render('CustomerDetails');
})->name('customer.details');

Route::get('/customer-checkout', function () {
    return Inertia::render('Checkout');
})->name('customer.checkout');


Route::get('/customer-confirmation', function () {
    return Inertia::render('Confirmation');
})->name('customer.confirmation');


Route::get('/couch/options', [CouchController::class, 'getQptions'])->name('couch.options');

Route::post('/customer/quote', [QuoteController::class, 'getQuote'])->name('customer.quote');

Route::post('/customer/create', [CustomerController::class, 'store'])->name('customer.create');

Route::post('/customer/payment', [CheckoutController::class, 'checkout'])->name('customer.payment');

Route::post('/customer/process', [OrderController::class, 'process'])->name('customer.process');

Route::get('/test-error', function () {
    throw new Exception('Test exception for logging.');
});
