<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes 
Route::post('/user-register',[UserController::class,'register']); 
Route::post('/user-login',[UserController::class,'login']); 
Route::middleware('jwt.auth')->get('/verify-user', [UserController::class, 'verifyUser']);
Route::middleware('jwt.auth')->post('/logout', [UserController::class, 'logout']);

//Admin Routes 
Route::middleware('jwt.auth')->post('/admin/upload/image', [AdminController::class, 'uploadImage']);
Route::middleware('jwt.auth')->post('/admin/upload/dashboard', [AdminController::class, 'uploadDashboard']);
Route::middleware('jwt.auth')->get('/admin/fetch/dashboard', [AdminController::class, 'fetchDashboard']);
Route::middleware('jwt.auth')->delete('/admin/delete/dashboard/{id}', [AdminController::class, 'deleteDashboard']);

