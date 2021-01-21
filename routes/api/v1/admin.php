<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\CourseController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;

Route::group([
    'prefix' => 'admin'
], function () {
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('categories', CategoryController::class);    
});
