<?php

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

// Route::get('/', function () {

//     return view('home');
// });

// Route::view('/', 'HomeController@home')->name('home');



// Route::get('/contact', function () {

//     return view('contact');
// });
 
Route::get('/', 'HomeController@home')->name('home');

Route::get('contact', 'HomeController@contact')->name('contact');

Route::resource('posts', 'PostController');
	// ->except('destroy');

		// ->only(['index', 'show', 'create','store']);

Route::get('check', 'CheckController@test');