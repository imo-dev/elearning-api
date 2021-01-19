<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\CourseController;

Route::apiResource('admin/courses', CourseController::class);
