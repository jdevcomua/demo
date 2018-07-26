<?php

Route::get('/', 'SiteController')->name('index');

Route::view('/about', 'about')->name('about');
Route::view('/faq', 'faq')->name('faq');


// Authentication Routes...
Route::get('login', 'SiteController@login')->name('login');
Route::get('auth/callback', 'SiteController@loginCallback');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::group(['middleware' => 'auth'], function () {

    Route::resource('pets', 'PetsController')
        ->only(['index', 'create', 'store', 'show', 'update']);

    Route::match(['get', 'post'], '/profile', 'ProfileController')->name('profile');

});

Route::group([
    'prefix' => '/ajax',
    'as' => 'ajax.',
    'middleware' => ['auth'],
], function () {

    Route::get('/species/{species}/breeds', 'AjaxController@getBreeds')->name('getBreeds');
    Route::get('/species/{species}/colors', 'AjaxController@getColors')->name('getColors');

});
