<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes 
Route::post('/user-register',[UserController::class,'register']); 
Route::post('/user-login',[UserController::class,'login']); 

Route::middleware(['jwt.auth'])->group(function () {

    // Authenticated User Routes
    Route::get('/verify-user', [UserController::class, 'verifyUser']);
    Route::post('/logout', [UserController::class, 'logout']);

    // Admin Routes
    Route::post('/admin/upload/image', [AdminController::class, 'uploadImage']);
    Route::post('/admin/upload/dashboard', [AdminController::class, 'uploadDashboard']);
    Route::get('/admin/fetch/dashboard', [AdminController::class, 'fetchDashboard']);
    Route::delete('/admin/delete/dashboard/{id}', [AdminController::class, 'deleteDashboard']);
    
    Route::post('/admin/upload/product', [AdminController::class, 'uploadProduct']);
    Route::get('/admin/fetch/products', [AdminController::class, 'fetchProducts']);
    Route::put('/admin/update/product/{id}', [AdminController::class, 'updateProduct']);
    Route::delete('/admin/delete/product/{id}', [AdminController::class, 'deleteProduct']);
    
    // User Routes
    Route::get('/shop/fetch/products', [ShopController::class, 'fetchProducts']);
    Route::get('/shop/fetch/address/{id}', [ShopController::class, 'fetchAddress']);
    Route::post('/shop/upload/address', [ShopController::class, 'uploadAddress']);
    Route::put('/shop/update/address/{id}', [ShopController::class, 'updateAddress']);
    Route::delete('/shop/delete/address/{id}', [ShopController::class, 'deleteAddress']);
    
    Route::get('/shop/fetch/order/{id}', [ShopController::class, 'fetchOrder']);
    Route::get('/shop/fetch/orderDetail/{id}', [ShopController::class, 'fetchOrderDetail']);
    Route::put('/shop/update/orderStatus/{id}/{value}', [ShopController::class, 'updateOrderStatus']);

    //Order Routes
    Route::post('/user/place-order', [ShopController::class, 'placeOrder']);

});

