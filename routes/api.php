<?php

use App\Http\Controllers\V1\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::get('/users', [UserController::class, 'index'])
    ->name('v1.users.index');
    Route::get('/users/{uuid}', [UserController::class, 'show'])
        ->name('v1.users.show');
    Route::post('/users', [UserController::class, 'store'])
        ->name('v1.users.store');
});
