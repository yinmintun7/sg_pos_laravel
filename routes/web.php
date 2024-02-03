<?php

use App\Http\Controllers\Item\ItemController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Shift\ShiftController;
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
// Route::get('/test', [TestController::class, 'index']);
// Route::get('/login', [LoginController::class, 'loginForm']);
// Route::get('/logout', [LoginController::class, 'logout']);
// Route::post('/login/authLogin', [LoginController::class, 'authLogin'])->name('AuthLogin');

Route::get('/sg-backend/login', [LoginController::class, 'getLoginForm']);
Route::get('/sg-backend/logout', [LoginController::class, 'logout']);
Route::get('/sg-backend/unauthorze', [LoginController::class, 'unauthorize']);
Route::post('/postlogin', [LoginController::class, 'postLoginForm'])->name('sg-backendLogin');


Route::group(['prefix' => 'sg-backend', 'middleware' => 'admin'], function () {
    Route::get('/index', [DashboardController::class, 'index']);

    Route::group(['prefix' => 'shift'], function () {
        Route::get('/index', [ShiftController::class, 'index']);
        Route::get('/start', [ShiftController::class, 'start']);
        Route::get('/end', [ShiftController::class, 'end']);
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/', [CategoryController::class, 'form']);
        // Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/create', [CategoryController::class, 'create'])->name('storeCategory');
        Route::get('/list', [CategoryController::class, 'getCategory']);
        Route::get('/edit/{id}', [CategoryController::class, 'categoryEditForm']);
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

});
// Route::group(['prefix' => 'test', 'middleware' => 'admin'], function () {
//     Route::get('/show_form', [TestController::class, 'showForm']);
//     Route::post('/store_data', [TestController::class, 'storeForm'])->name('StoreForm');
//     Route::get('/list', [TestController::class, 'showList'])->name('showList');
//     Route::get('/edit/{id}', [TestController::class, 'editForm']);
//     Route::post('/update', [TestController::class, 'updateForm'])->name('UpdateForm');
//     Route::get('/delete/{id}', [TestController::class, 'deleteForm'])->name('DeleteForm');
// });
