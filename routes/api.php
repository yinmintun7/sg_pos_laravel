<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;

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
Route::post('/postFrontendlogin', [LoginController::class, 'postFrontendlogin'])->name('frontendLogin');
Route::middleware('auth:sanctum', 'cashier')->group(function () {
    Route::get('/test', function () {
        dd('hi');
    });

});
