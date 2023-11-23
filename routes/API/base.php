<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Other\FileManagerController;
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->group(function () {
        /*
         * be careful with this route, because anyone can call it!
         * TODO: make this route more secure (I think in controller).
         */
        Route::any('payments/{payment}/verify', [PaymentController::class, 'verify'])
            ->name('payment.verify');

        Route::get('files/{file}/{size?}', [FileManagerController::class, 'show'])
            ->name('files.show');

        Route::post('login', [AuthController::class, 'login'])
            ->name('login');

//        log_visit => it is middleware alias

    });
