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

Route::get('/', 'SiteController')->name('index');

Route::view('/about', 'about')->name('about');
Route::view('/faq', 'faq')->name('faq');

Route::view('/profile', 'profile')->name('profile');


// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::group(['middleware' => 'auth'], function () {

    Route::resource('pets', 'PetsController')
        ->only(['index', 'create', 'store', 'show', 'update']);

    Route::match(['get', 'post'], '/profile', 'ProfileController')->name('profile');

});

Route::group([
    'prefix' => '/admin',
    'as' => 'admin.',
    'middleware' => ['auth'],
], function () {

    Route::get('/home', 'HomeController@index');

});

Route::get('test', function () {
    $user = \App\User::find(2);
    Auth::login($user);
    return redirect('/', 302);
})->name('test-login');