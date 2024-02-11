<?php

use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Shift\ShiftController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\User\UserController;
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
        Route::get('/list', [DiscountController::class, 'getItems']);
        Route::get('/edit/{id}', [DiscountController::class, 'itemEditForm']);
        Route::post('/update', [DiscountController::class, 'updateItem'])->name('updateItem');
        Route::post('/delete', [DiscountController::class, 'deleteItem'])->name('deleteItem');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'form']);
        Route::post('/create', [UserController::class, 'create'])->name('store');
        Route::get('/list', [UserController::class, 'list']);
        Route::get('/edit/{id}', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::post('/delete', [UserController::class, 'delete'])->name('delete');
    });
});
