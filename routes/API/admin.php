<?php

use App\Http\Controllers\Admin\FileManagerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('api.admin.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');

            Route::get('role', [RoleController::class, 'index'])->name('roles.index');

            /*
             * user routes
             */
            Route::delete('users/batch', [UserController::class, 'batchDestroy'])
                ->name('users.destroy.batch');
            Route::apiResource('users', UserController::class)->whereNumber('users');

            /*
             * file-manager routes
             */
            Route::get('files', [FileManagerController::class, 'index'])
                ->name('files.index');
            Route::get('files/tree', [FileManagerController::class, 'treeList'])
                ->name('files.tree');
            Route::post('files/directory', [FileManagerController::class, 'createDirectory'])
                ->name('files.create-directory');
            Route::post('files/rename', [FileManagerController::class, 'rename'])
                ->name('files.rename');
            Route::post('files/move', [FileManagerController::class, 'move'])
                ->name('files.move');
            Route::post('files', [FileManagerController::class, 'store'])->middleware('optimizeImages')
                ->name('files.store');
            Route::get('files/{file}', [FileManagerController::class, 'download'])
                ->name('files.download');
            Route::get('files/{file}/{size?}', [FileManagerController::class, 'show'])
                ->name('files.show');
            Route::delete('files/{file}', [FileManagerController::class, 'destroy'])
                ->name('files.destroy')->whereNumber('file');
            Route::delete('files/batch', [FileManagerController::class, 'batchDestroy'])
                ->name('files.destroy.batch');
        });
    });
