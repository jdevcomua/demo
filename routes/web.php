<?php

use Illuminate\Http\Request;

Route::get('/b', 'SiteController@badgeData');

Route::get('/', 'SiteController')->name('index');

Route::view('/about', 'about')->name('about');
Route::get('/faq', 'SiteController@faq')->name('faq');

Route::view('/bad-login', 'errors/bad-login')->name('bad-login');

Route::view('/banned', 'errors/banned')->name('banned');

// Authentication Routes...
Route::get('login', 'AuthController@login')->name('login');
Route::get('auth/attempt', 'AuthController@loginAttempt');
Route::get('auth/callback', 'AuthController@loginCallback');
Route::post('logout', 'AuthController@logout')->name('logout');


Route::group(['middleware' => ['not.banned', 'not.phone.missing']], function () {
    Route::get('lost-animals/found', 'AnimalsLostController@foundIndex')->name('lost-animals.found');
    Route::get('lost-animals/lost/show/{id}', 'AnimalsLostController@lostShow')->name('lost-animals.lost.show');
    Route::post('lost-animals/i-found-animal', 'AnimalsLostController@iFoundAnimal')->name('lost-animals.i-found-animal');
    Route::resource('lost-animals', 'AnimalsLostController');

    Route::group(['middleware' => 'auth'], function () {
        Route::resource('animals', 'AnimalsController');
        Route::post('animals/search-request', 'AnimalsController@findAnimalRequest')->name('animals.search-request');
        Route::post('/animals/file/{animalFile}/remove', 'AnimalsController@removeFile')->name('animals.remove-file');
        Route::view('/animals/verify', 'animals.verify')->name('animals.verify');
        Route::view('/animals/scan', 'animals.scan')->name('animals.scan');
        Route::post('/animals/search', 'AnimalsController@search')->name('animals.search');

        Route::post('{animal}/lost', 'AnimalsController@lost')->name('animals.lost');

        Route::post('/animals/change-owner', 'AnimalsController@changeOwner')->name('animals.change-owner');

        Route::post('/animals/inform-death', 'AnimalsController@informDeath')->name('animals.inform-death');
        Route::post('/animals/inform-moved', 'AnimalsController@informMoved')->name('animals.inform-moved');

        Route::get('/profile', 'ProfileController@show')->name('profile');
        Route::post('/profile', 'ProfileController@update')->name('profile.update');

    });



    Route::group([
        'prefix' => '/ajax',
        'as' => 'ajax.',
    ], function () {
        Route::get('/species/{species}/breeds', 'AjaxController@getBreeds')
            ->name('getBreeds');
        Route::get('/species/{species}/colors', 'AjaxController@getColors')
            ->name('getColors');

        Route::group(['middleware' => ['auth']], function () {
            Route::get('/species/{species}/furs', 'AjaxController@getFurs')
                ->name('getFurs');
            Route::get('/users', 'AjaxController@getUsers')
                ->name('getUsers');
            Route::post('/search', 'AjaxController@badgeSearch')
                ->name('animals.search');
            Route::post('/animal/request', 'AjaxController@requestAnimal')
                ->name('animals.request');
            Route::get('/organizations', 'AjaxController@getOrganizations')
                ->name('getOrganizations');
        });
    });

});

Route::group(['middleware' => [
    'not.banned',
    'auth'
]], function () {

    Route::get('/profile/phone-missing', 'ProfileController@phoneMissing')->name('profile.phone-missing');
    Route::post('/profile/phone-missing/update', 'ProfileController@phoneMissingUpdate')->name('profile.phone-missing.update');

});

Route::get('/login/as/admin', 'AuthController@loginAsAdmin');
