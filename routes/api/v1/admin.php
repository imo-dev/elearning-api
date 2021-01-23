<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\CourseController;
use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\Course\AssignmentController as CourseAssignmentController;
use App\Http\Controllers\Api\V1\Admin\Course\MaterialController as CourseMaterialController;
use App\Http\Controllers\Api\V1\Admin\Course\QuizController as CourseQuizController;
use App\Http\Controllers\Api\V1\Admin\Course\TopicController as CourseTopicController;

Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth:api', 'role:admin']
], function () {
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('categories', CategoryController::class);
    
    // categories
    Route::group(['prefix' => 'courses'], function () {
        Route::apiResource('{course}/topics', CourseTopicController::class);
        Route::apiResource('{course}/materials', CourseMaterialController::class);
        Route::apiResource('{course}/assignment', CourseAssignmentController::class);
        Route::apiResource('{course}/quiz', CourseQuizController::class);
    });
});
