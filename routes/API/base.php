<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\CaptchaController;

Route::name('api.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])
            ->name('login');
    });
