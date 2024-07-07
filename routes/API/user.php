<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\User\UserBlogCommentController;
use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserContactUsController;
use App\Http\Controllers\User\UserFavoriteProductController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserReturnOrderRequestController;
use App\Http\Controllers\User\UserSpecificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')
    ->name('api.user.')
    ->middleware('auth:sanctum')
    ->group(function () {
        $codeRegex = '[\d\w\-\_]+';

        Route::post('logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::get('count-of-stuffs', [UserHomeController::class, 'countOfItems'])->name('home.count-of-stuffs');

        /*
         * specification routes
         */
        Route::get('info', [UserSpecificationController::class, 'showInfo'])->name('show.info');
        Route::put('info', [UserSpecificationController::class, 'updateInfo'])->name('update.info');
        Route::put('password', [UserSpecificationController::class, 'updatePassword'])->name('update.password');

        /*
         * notification routes
         */
        Route::get('notifications', [UserNotificationController::class, 'index'])->name('notifications.index');
        Route::put('notifications', [UserNotificationController::class, 'update'])->name('notifications.update');
        Route::get('notifications/new', [UserNotificationController::class, 'newNotifications'])->name('notifications.new');

        /*
         * order routes
         */
        Route::get('orders/unpaid-order-payments', [UserOrderController::class, 'unpaidOrderPayments'])
            ->name('orders.unpaid-order-payments');
        Route::get('orders/latest', [UserOrderController::class, 'latest'])->name('orders.latest');
        Route::apiResource('orders', UserOrderController::class)->except(['store', 'destroy'])
            ->where(['order' => $codeRegex]);

        /*
         * return order routes
         */
        Route::post('return-orders/{return_order}/change-status', [UserReturnOrderRequestController::class, 'changeStatus'])
            ->where(['return_order' => $codeRegex])->name('return-orders.change-status');
        Route::post('return-orders/{order}', [UserReturnOrderRequestController::class, 'store'])
            ->where(['order' => $codeRegex])->name('return-orders.store');
        Route::get('return-orders/latest', [UserReturnOrderRequestController::class, 'latest'])->name('return-orders.latest');
        Route::get('return-orders/returnable-orders', [UserReturnOrderRequestController::class, 'returnableOrders'])
            ->name('return-orders.returnable-orders');
        Route::apiResource('return-orders', UserReturnOrderRequestController::class)->except(['store'])
            ->where(['return_order' => $codeRegex]);

        /*
         * comment routes
         */
        Route::get('product/comments', [UserCommentController::class, 'index'])
            ->name('product.comments.index');
        Route::get('product/comments/{comment}', [UserCommentController::class, 'show'])
            ->whereNumber('comment')->name('product.comments.show');
        Route::post('product/{product}/comments', [UserCommentController::class, 'store'])
            ->where(['product' => $codeRegex])->name('product.comments.store');
        Route::put('product/comments/{comment}', [UserCommentController::class, 'update'])
            ->whereNumber('comment')->name('product.comments.update');
        Route::delete('product/comments/{comment}', [UserCommentController::class, 'destroy'])
            ->whereNumber('comment')->name('product.comments.destroy');

        /*
         * blog comment routes
         */
        Route::get('blog/comments', [UserBlogCommentController::class, 'index'])
            ->name('blog.comments.index');
        Route::get('blog/comments/{comment}', [UserBlogCommentController::class, 'show'])
            ->whereNumber('comment')->name('blog.comments.show');
        Route::post('blog/{blog}/comments', [UserBlogCommentController::class, 'store'])
            ->where(['blog' => $codeRegex])->name('blog.comments.store');
        Route::put('blog/comments/{comment}', [UserBlogCommentController::class, 'update'])
            ->whereNumber('comment')->name('blog.comments.update');
        Route::delete('blog/comments/{comment}', [UserBlogCommentController::class, 'destroy'])
            ->whereNumber('comment')->name('blog.comments.destroy');

        /*
         * favorite product routes
         */
        Route::get('favorite-products', [UserFavoriteProductController::class, 'index'])
            ->name('favorite-products.index');
        Route::post('favorite-products', [UserFavoriteProductController::class, 'store'])
            ->name('favorite-products.store');
        Route::delete('favorite-products/{product}', [UserFavoriteProductController::class, 'destroy'])
            ->where(['product' => $codeRegex])->name('favorite-products.destroy');

        /*
         * address routes
         */
        Route::apiResource('addresses', UserAddressController::class)
            ->whereNumber('address');

        /*
         * contact routes
         */
        Route::apiResource('contacts', UserContactUsController::class)->only(['index', 'show', 'destroy'])
            ->whereNumber('contact');
    });
