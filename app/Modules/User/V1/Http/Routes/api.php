<?php

namespace App\Modules\User\Http\Routes;

use App\Modules\User\V1\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'api/v1'], function () {

   Route::prefix('auth')->group(function () {
       Route::post('/user', [UserController::class, 'store'])->name('api.auth.user.store');
   });

});
