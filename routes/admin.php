<?php

Route::redirect('/', '/admin/animals/index', 302)
    ->name('index');

//  TODO move data routes to another route grope and make it secure
// since Entrust checking permissions every request
// -> more DB requests -> slower app -> less req/sec


Route::group([
    'prefix' => 'users',
    'as' => 'db.users.'
], function () {
    Route::get('index', 'Admin\DataBasesController@userIndex')
        ->name('index');
    Route::get('data', 'Admin\DataBasesController@userData')
        ->name('data');
    Route::get('show/{id?}', 'Admin\DataBasesController@userShow')
        ->name('show');
    Route::put('update/{id?}', 'Admin\DataBasesController@userUpdate')
        ->name('update');
    Route::delete('delete/{id?}', 'Admin\DataBasesController@userDelete')
        ->name('remove');
});

Route::group([
    'prefix' => 'animals',
    'as' => 'db.animals.'
], function () {
    Route::get('index', 'Admin\DataBasesController@animalIndex')
        ->name('index');
    Route::get('data', 'Admin\DataBasesController@animalData')
        ->name('data');
    Route::get('edit/{id?}', 'Admin\DataBasesController@animalEdit')
        ->name('edit');
    Route::put('update/{id}', 'Admin\DataBasesController@animalUpdate')
        ->name('update');
    Route::get('remove/{id?}', 'Admin\DataBasesController@animalRemove')
        ->name('remove');
    Route::get('verify/{id}', 'Admin\DataBasesController@animalVerify')
        ->name('verify')
        ->middleware('permission:verify-animal');

});


Route::group([
    'prefix' => 'directories',
    'as' => 'info.directories.'
], function () {
    Route::get('index', 'Admin\InfoController@directoryIndex')
        ->name('index');

    Route::get('data/breed', 'Admin\InfoController@directoryDataBreed')
        ->name('data.breed');
    Route::post('store/breed', 'Admin\InfoController@directoryStoreBreed')
        ->name('store.breed');
    Route::get('remove/breed', 'Admin\InfoController@directoryRemoveBreed')
        ->name('remove.breed');

    Route::get('data/color', 'Admin\InfoController@directoryDataColor')
        ->name('data.color');
    Route::post('store/color', 'Admin\InfoController@directoryStoreColor')
        ->name('store.color');
    Route::get('remove/color', 'Admin\InfoController@directoryRemoveColor')
        ->name('remove.color');
});


Route::group([
    'prefix' => 'roles',
    'as' => 'roles.'
], function () {
    Route::get('/', 'Admin\AdminRolesController@index')
        ->name('index');
    Route::post('/', 'Admin\AdminRolesController@store')
        ->name('store');
    Route::get('edit/{id?}', 'Admin\AdminRolesController@edit')
        ->name('edit');
    Route::post('update/{id}', 'Admin\AdminRolesController@update')
        ->name('update');
    Route::get('remove/{id?}', 'Admin\AdminRolesController@remove')
        ->name('remove');
    Route::get('data', 'Admin\AdminRolesController@rolesData')
        ->name('data');
});




