<?php

use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

if (!app()->isProduction()) {
    Route::get('/', function () {
        // ...
    });
}

Route::middleware('xss')->group(function () {
    /*
     * be careful with this route, because anyone can call it!
     */
    Route::any('payments/{payment}/verify', [PaymentController::class, 'verify'])
        ->name('payment.verify');
});
