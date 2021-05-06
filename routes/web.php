<?php

use Illuminate\Support\Facades\Route;

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
$posts = [
    1 => [
        'title' => 'title 1',
        'content' => 'content 1',
        'is_new' => true
    ],
    2 => [
        'title' => 'title 2',
        'content' => 'content 2',
        'is_new' => false
    ],
    3 => [
        'title' => 'title 3',
        'content' => 'content 3',
        'is_new' => false
    ]
];

Route::name('home.')->group(function(){
    Route::view('/', 'home.index')->name('index');
    Route::view('/contact', 'home.contact')->name('contact');
});

Route::get('/demo/{id}', function($id) use ($posts){

    abort_if(!isset($posts[$id]), 404);
    return view('home.demo', ['post' => $posts[$id]]);
})->name('demo.show');

Route::get('/demos', function() use ($posts){
    return view('home.demo_index', compact('posts'));
})->name('demo.index');

Route::get('/response', function() use ($posts){
    return response($posts, 200)
           ->header('Content-Type', 'application/json')
           ->cookie('COOKIE_NAME', 'COOKIE_VALUE', 3600);
})->name('response');

Route::get('/redirect', function(){
    return redirect('/contact');
});
