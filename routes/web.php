<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => IsAdmin::class, 'prefix' => '/admin'], function() {
    Route::any('/update/{id}', [AdminController::class, 'update'])->name('admin-update');
    Route::get('/delete/{id}', [AdminController::class, 'delete'])->name('admin-delete');
    Route::get('/', [AdminController::class, 'getAll'])->name('admin');
});
