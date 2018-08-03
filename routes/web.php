<?php

Route::get('/', 'SiteController')->name('index');

Route::view('/about', 'about')->name('about');
Route::get('/faq', 'SiteController@faq')->name('faq');

Route::view('/bad-login', 'errors/bad-login')->name('bad-login');


// Authentication Routes...
Route::get('login', 'SiteController@login')->name('login');
Route::get('auth/attempt', 'SiteController@loginAttempt');
Route::get('auth/callback', 'SiteController@loginCallback');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::group(['middleware' => 'auth'], function () {

    Route::resource('animals', 'AnimalsController');
    Route::post('/animals/file/{animalFile}/remove', 'AnimalsController@removeFile')->name('animals.remove-file');
    Route::view('/animals/add-success', 'animals.add-success')->name('animals.add-success');

    Route::get('/profile', 'ProfileController@show')->name('profile');
    Route::post('/profile', 'ProfileController@update')->name('profile.update');

});

Route::group([
    'prefix' => '/ajax',
    'as' => 'ajax.',
    'middleware' => ['auth'],
], function () {

    Route::get('/species/{species}/breeds', 'AjaxController@getBreeds')->name('getBreeds');
    Route::get('/species/{species}/colors', 'AjaxController@getColors')->name('getColors');

});

Route::get('/login/as/admin', 'SiteController@loginAsAdmin');
Route::get('/login/as/user', 'SiteController@loginAsUser');
