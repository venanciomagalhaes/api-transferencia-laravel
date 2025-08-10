<?php


use App\Modules\Transaction\V1\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('/transfer', [TransactionController::class, 'transfer'])->name('api.v1.transaction.transfer');
});

