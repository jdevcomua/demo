<?php

Route::get('/', [
    'uses' => 'Admin\AdminController@index',
    'as' => 'index'
]);


Route::group([
    'prefix' => 'users',
    'as' => 'users.'
], function () {

    Route::get('/', [
        'uses' => 'Admin\UsersController@index',
        'as' => 'index'
    ]);

    Route::get('data', [
        'uses' => 'Admin\UsersController@getUsersData',
        'as' => 'data'
    ]);

    Route::get('{id}/show', [
        'uses' => 'Admin\UsersController@show',
        'as' => 'show'
    ]);

    Route::put('{id}/update', [
        'uses' => 'Admin\UsersController@update',
        'as' => 'update'
    ]);

    Route::get('administrate', [
        'uses' => 'Admin\UsersController@administrate',
        'as' => 'administrate'
    ]);

    Route::get('administrate/data', [
        'uses' => 'Admin\UsersController@getUsersAdministrativeData',
        'as' => 'administrate.data'
    ]);

    Route::post('administrate/{id}/role', [
        'uses' => 'Admin\UsersController@changeRole',
        'as' => 'administrate.role'
    ]);

    Route::get('bans', [
        'uses' => 'Admin\UsersController@bans',
        'as' => 'bans'
    ]);

    Route::get('bans/data', [
        'uses' => 'Admin\UsersController@getUsersBansData',
        'as' => 'bans.data'
    ]);

    Route::post('bans/{id}/ban', [
        'uses' => 'Admin\UsersController@banUser',
        'as' => 'bans.ban'
    ]);
});

Route::group([
    'prefix' => 'animals',
    'as' => 'animals.'
], function () {

    Route::get('/', [
        'uses' => 'Admin\AnimalsController@index',
        'as' => 'index'
    ]);

    Route::get('{id}/show', [
        'uses' => 'Admin\AnimalsController@show',
        'as' => 'show'
    ]);

    Route::put('{id}/update', [
        'uses' => 'Admin\AnimalsController@update',
        'as' => 'update'
    ]);

    Route::post('{id}/confirm', [
        'uses' => 'Admin\AnimalsController@confirm',
        'as' => 'confirm'
    ]);

    Route::get('/archive', [
        'uses' => 'Admin\AnimalsController@archive',
        'as' => 'archive'
    ]);

    Route::get('/data', [
        'uses' => 'Admin\AnimalsController@getAnimalsNewData',
        'as' => 'data'
    ]);

    Route::get('/archive/data', [
        'uses' => 'Admin\AnimalsController@getAnimalsArchiveData',
        'as' => 'archive.data'
    ]);
});






