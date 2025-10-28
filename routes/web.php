<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\UserController;

Route::middleware(['auth'])->group(function () {

    //dashboard
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // categories 
    Route::middleware(['admin_auth'])->group(function () {
        //user list 

        Route::get('user/list', [AdminController::class, 'userList'])->name('user#list');
        Route::get('user/changeUserRole', [AdminController::class, 'changeUserRole'])->name('user#changeUserRole');
        //categories 
        Route::prefix('category')->group(function () {
            Route::get('list', [CategoryController::class, 'list'])->name('category#list');
            Route::get('create', [CategoryController::class, 'createPage'])->name('category#createPage');
            Route::post('create', [CategoryController::class, 'create'])->name('category#create');
            Route::get('delete/{id}', [CategoryController::class, 'delete'])->name('category#delete');
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->name('category#edit');
            Route::post('update', [CategoryController::class, 'update'])->name('category#update');
        });


        //products
        Route::prefix('product')->group(function() {
            Route::get('list', [ProductController::class, 'list'])->name('product#list');
            Route::get('new', [ProductController::class, 'new'])->name('product#new');
            Route::post('create', [ProductController::class, 'create'])->name('product#create');
            Route::get('edit/{id}', [ProductController::class, 'edit'])->name('product#edit');
            Route::post('update/{id}', [ProductController::class, 'update'])->name('product#update');
            Route::get('delete{id}', [ProductController::class, 'delete'])->name('product#delete');
            Route::get('show/{id}', [ProductController::class, 'show'])->name('product#show');
        });

        //admin
        Route::prefix('admin/account')->group(function() {
            Route::get('list', [AdminController::class, 'list'])->name('admin#list');
            Route::get('change-password', [AdminController::class, 'changePasswordPage'])->name('admin#changePasswordPage');
            Route::post('update-password', [AdminController::class, 'updatePassword'])->name('admin#updatePassword');
            Route::get('detail', [AdminController::class, 'detail'])->name('admin#detail');
            Route::get('edit', [AdminController::class, 'edit'])->name('admin#edit');
            Route::post('update/{id}', [AdminController::class, 'update'])->name('admin#update');
            Route::get('delete/{id}', [AdminController::class, 'delete'])->name('admin#delete');
            Route::get('changeRole/{id}', [AdminController::class, 'changeRole'])->name('admin#changeRole');
            Route::post('updateRole/{id}', [AdminController::class, 'updateRole'])->name('admin#updateRole');
        });

        // orders 
        Route::prefix('order')->group(function () {
            Route::get('list', [OrderController::class, 'list'])->name('order#list');
            Route::get('status', [AjaxController::class, 'orderStatus'])->name('ajax#orderStatus');
            Route::get('filter', [AjaxController::class, 'filterOrders'])->name('ajax#filterOrders');

        });
    });

    //user
    Route::prefix('user')->middleware('user_auth')->group(function () {
        // users 
        Route::get('home', [UserController::class, 'home'])->name('user#home');
        Route::get('home/{id}', [UserController::class, 'filter'])->name('user#filter');
        Route::get('pizza/{id}', [UserController::class, 'show'])->name('user#show');
        Route::get('changePassword', [UserController::class, 'changePassword'])->name('user#changePassword');
        Route::post('updatePassword', [UserController::class, 'updatePassword'])->name('user#updatePassword');
        Route::get('view', [UserController::class, 'view'])->name('user#view');
        Route::get('edit/{id}', [UserController::class, 'edit'])->name('user#edit');
        Route::post('update/{id}', [UserController::class,'update'])->name('user#update');

        // carts
        Route::get('carts', [UserController::class, 'carts'])->name('user#carts');
        // orders
        Route::get('history', [UserController::class, 'history'])->name('user#history');




        //ajax
        Route::prefix('ajax')->group(function () {
            Route::get('pizzas', [AjaxController::class, 'pizzaList'])->name('ajax#pizzaList');
            Route::get('addToCart', [AjaxController::class, 'addToCart'])->name('ajax#addToCart');
            Route::get('autoAddToCart', [AjaxController::class, 'autoAddToCart'])->name('ajax#autoAddToCart');
            Route::get('order', [AjaxController::class, 'order'])->name('ajax#order');
            Route::get('clear/cart', [AjaxController::class, 'clearCart'])->name('ajax#clearCart');
            Route::get('remove', [AjaxController::class, 'remove'])->name('ajax#remove');
        });
    });

    Route::get('orderList/{id}', [OrderController::class, 'orderList'])->name('orderList');
});

// login, register
Route::middleware(['admin_auth'])->group(function() {
    Route::redirect('/', 'loginPage');
    Route::get('loginPage',[AuthController::class, 'loginPage'])->name('auth#loginPage');
    Route::get('registerPage', [AuthController::class, 'registerPage'])->name('auth#registerPage');
});
