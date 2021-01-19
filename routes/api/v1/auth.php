<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me']);
});