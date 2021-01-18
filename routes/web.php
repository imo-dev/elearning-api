<?php

use App\Helpers\DataTable;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    try {
        $pdo = DB::connection()->getPdo();
        $datatable = (new DataTable)->of(User::query())->make();
        return apiResponse(
            $datatable,
            'success connect to db',
            true
        );
    } catch (\Throwable $th) {
        return "gagal connect db - " . $th->getMessage();
    }
});