<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes to autentication
Route::prefix('auth')->group(function () {
    Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');

    Route::middleware('auth:api')->group(function (){
        Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    });
});

// Route to edit permitions
Route::prefix('permission')->group(function (){
    Route::get('/', [App\Http\Controllers\PermissionController::class, 'index'])->middleware('auth:api');

    Route::post('/create', [App\Http\Controllers\PermissionController::class, 'store'])->middleware('auth:api');

    Route::post('/delete', [App\Http\Controllers\PermissionController::class, 'destroy'])->middleware('auth:api');
});

