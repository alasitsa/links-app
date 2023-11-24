<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LoginController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [LoginController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('/patch/{id?}', [HomeController::class, 'patch'])->name('patch-api');
    Route::get('/delete/{id}', [HomeController::class, 'delete'])->name('delete-api');
    Route::get('/', [HomeController::class, 'getAll'])->name('home-api');

    Route::group(['middleware' => 'admin:api', 'prefix' => '/admin'], function () {
        Route::post('/patch/{id}', [AdminController::class, 'patch'])->name('admin-patch-api');
        Route::get('/delete/{id}', [AdminController::class, 'delete'])->name('admin-delete-api');
        Route::get('/', [AdminController::class, 'getAll'])->name('admin-api');
    });
});
