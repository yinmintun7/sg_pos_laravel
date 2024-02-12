<?php

use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Shift\ShiftController;
use App\Http\Controllers\Discount\DiscountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\test\TestController;

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

Route::get('/sg-backend/login', [LoginController::class, 'getLoginForm']);
Route::get('/sg-backend/logout', [LoginController::class, 'logout']);
Route::get('/sg-backend/unauthorze', [LoginController::class, 'unauthorize']);
Route::post('/postlogin', [LoginController::class, 'postLoginForm'])->name('sg-backendLogin');


Route::group(['prefix' => 'sg-backend', 'middleware' => 'admin'], function () {
    Route::get('/index', [DashboardController::class, 'index']);

    Route::group(['prefix' => 'shift'], function () {
        Route::get('/index', [ShiftController::class, 'index']);
        Route::post('/start', [ShiftController::class, 'start']);
        Route::post('/end', [ShiftController::class, 'end']);
        Route::get('start', [ShiftController::class, 'redirectTo404']);
        Route::get('end', [ShiftController::class, 'redirectTo404']);

    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'form']);
        Route::post('/create', [CategoryController::class, 'create'])->name('storeCategory');
        Route::get('/list', [CategoryController::class, 'getCategory']);
        Route::get('/edit/{id}', [CategoryController::class, 'editCategory']);
        Route::post('/update', [CategoryController::class, 'updateCategory'])->name('updateCategory');
        Route::post('/delete', [CategoryController::class, 'deleteCategory'])->name('deleteCategory');
    });

    Route::group(['prefix' => 'item'], function () {
        Route::get('/', [ItemController::class, 'form']);
        Route::post('/create', [ItemController::class, 'create'])->name('storeItem');
        Route::get('/list', [ItemController::class, 'getItems']);
        Route::get('/edit/{id}', [ItemController::class, 'itemEditForm']);
        Route::post('/update', [ItemController::class, 'updateItem'])->name('updateItem');
        Route::post('/delete', [ItemController::class, 'deleteItem'])->name('deleteItem');
    });

    Route::group(['prefix' => 'discount'], function () {
        Route::get('/', [DiscountController::class, 'form']);
        Route::post('/create', [DiscountController::class, 'create'])->name('storeDiscount');
        Route::get('/list', [DiscountController::class, 'getDiscount']);
        Route::get('/edit/{id}', [DiscountController::class, 'edit']);
        Route::post('/update', [DiscountController::class, 'updateDiscount'])->name('updateDiscount');
        Route::post('/delete', [DiscountController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'setting'], function () {
        Route::get('/', [SettingController::class, 'form']);
        Route::post('/create', [SettingController::class, 'create'])->name('storeSetting');
        Route::get('/list', [DiscountController::class, 'getDiscount']);
        Route::get('/edit/{id}', [DiscountController::class, 'edit']);
        Route::post('/update', [DiscountController::class, 'update'])->name('updateSetting');
        Route::post('/delete', [DiscountController::class, 'delete'])->name('delete');
    });

});
