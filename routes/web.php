<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;

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

Route::name('home.')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});

Route::resource('posts', PostsController::class)->only(['index', 'show']);
