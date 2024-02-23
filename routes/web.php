<?php

use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Shift\ShiftController;
use App\Http\Controllers\Discount\DiscountController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Order\OrderController;
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
Route::get('/shift-close', [ShiftController::class, 'shiftClose']);
Route::get('/login', [LoginController::class, 'getFrontendLoginForm']);
Route::post('/postFrontendlogin', [LoginController::class, 'postFrontendlogin'])->name('frontendLogin');
Route::get('/logout', [LoginController::class, 'frontendLogout']);
Route::get('/unauthorze', [LoginController::class, 'unauthorize']);
Route::group(['prefix' => '/', 'middleware' => 'cashier'], function () {
    Route::get('/order-list', [OrderController::class, 'orderList']);
    Route::get('/order', [OrderController::class, 'order']);
    Route::post('/get-category', [OrderController::class, 'getCategory']);
    Route::post('/get-item', [OrderController::class, 'getAllItem']);
    Route::post('/get-item-by-cat', [OrderController::class, 'getItemByCategory']);
    Route::post('/get-item-data', [OrderController::class, 'getItemData']);
    Route::post('/store-order', [OrderController::class, 'storeOrder']);
    Route::post('/get-order-list', [OrderController::class, 'getOrderList']);
    Route::post('/cancel-order', [OrderController::class, 'CancelOrder']);
    Route::get('/order-edit/{id}', [OrderController::class, 'EditOrder']);
    Route::post('/get-order-items', [OrderController::class, 'getOrderItems']);
    Route::post('/update-order', [OrderController::class, 'updateOrder']);
    Route::get('/payment/{id}', [OrderController::class, 'getPaymentPage']);
    Route::post('/get-order-detail', [OrderController::class,'getOrderDetail']);
    Route::get('/order-detail-page/{id}', [OrderController::class,'getOrderDetailPage']);
    Route::post('/insert-paid-order', [OrderController::class,'insertPayOrder']);
    Route::get('/get-setting-data', [OrderController::class,'getSettingData']);
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
        Route::post('/update', [DiscountController::class, 'update'])->name('updateDiscount');
        Route::post('/delete', [DiscountController::class, 'delete']);
    });

    Route::group(['prefix' => 'setting'], function () {
        Route::get('/', [SettingController::class, 'form']);
        Route::post('/create', [SettingController::class, 'create'])->name('storeSetting');
        Route::get('/list', [SettingController::class, 'list']);
        Route::get('/edit', [SettingController::class, 'edit']);
        Route::post('/update', [SettingController::class, 'update'])->name('updateSetting');
        Route::post('/delete', [SettingController::class, 'delete']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'form']);
        Route::post('/create', [UserController::class, 'create'])->name('storeUser');
        Route::get('/list', [UserController::class, 'list']);
        Route::get('/edit/{id}', [UserController::class, 'edit']);
        Route::post('/update', [UserController::class, 'update'])->name('updateUser');
        Route::post('/delete', [UserController::class, 'delete'])->name('delete');
    });
});
