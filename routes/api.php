<?php

use App\Http\Controllers\Api\V1\TransferController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('user', [UserController::class, 'store']);
    Route::post('transfer', [TransferController::class, 'store']);
});
