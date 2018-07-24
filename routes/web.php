<?php

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

Route::get('test', function () {
    $user = \App\User::find(1);
    Auth::login($user);
    return redirect('/', 302);
})->name('test-login');