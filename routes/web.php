<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here you can register web routes for your application. These routes
| are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

Auth::routes();

// Default redirect after login
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
