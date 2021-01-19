<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// default gateway
Route::get('/', [HomeController::class, 'index']);


Route::group(['prefix' => 'v1'], function () {
    Route::get('/', [HomeController::class, 'v1']);
});

Route::get('/test', function () {
    try {
        DB::connection()->getPdo();
        return "ok";
    } catch (\Throwable $th) {
        return $th->getMessage();
    }
});