<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\TestAPIMailController;
use App\Http\Controllers\TestMailController;
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
Route::get('/heartbeat', function () {
    return response('All good');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-sentry', function () {
    #https://customer/debug-sentry
    throw new Exception('My first Sentry error!');
});

Route::get('/test-error', function () {
    throw new Exception('Test exception for logging.');
});

#generating bearer tokens for a user
Route::get('/generate-token', [TokenController::class, 'getToken']);

Route::get('/test-email-api', [TestAPIMailController::class, 'sendTestEmail']);
Route::get('/test-email-smtp', [TestMailController::class, 'sendTestEmail']);