<?php

Route::redirect('/', '/admin/animals/index', 302)
    ->name('index');

//  TODO move data routes to another route grope and make it secure
// since Entrust checking permissions every request
// -> more DB requests -> slower app -> less req/sec

Route::get('users/index', 'Admin\DataBasesController@userIndex')
    ->name('db.users');
Route::get('users/data', 'Admin\DataBasesController@userData')
    ->name('db.users.data');

Route::get('animals/index', 'Admin\DataBasesController@animalIndex')
    ->name('db.animals');
Route::get('animals/data', 'Admin\DataBasesController@animalData')
    ->name('db.animals.data');



Route::get('directories/index', 'Admin\InfoController@directoryIndex')
    ->name('info.directories');

Route::get('directories/data/breed', 'Admin\InfoController@directoryDataBreed')
    ->name('info.directories.data.breed');
Route::post('directories/store/breed', 'Admin\InfoController@directoryStoreBreed')
    ->name('info.directories.store.breed');
Route::get('directories/remove/breed', 'Admin\InfoController@directoryRemoveBreed')
    ->name('info.directories.remove.breed');

Route::get('directories/data/color', 'Admin\InfoController@directoryDataColor')
    ->name('info.directories.data.color');
Route::post('directories/store/color', 'Admin\InfoController@directoryStoreColor')
    ->name('info.directories.store.color');
Route::get('directories/remove/color', 'Admin\InfoController@directoryRemoveColor')
    ->name('info.directories.remove.color');



Route::get('roles', 'Admin\AdminRolesController@index')
    ->name('roles.index');
Route::post('roles', 'Admin\AdminRolesController@store')
    ->name('roles.store');
Route::get('roles/edit/{id?}', 'Admin\AdminRolesController@edit')
    ->name('roles.edit');
Route::post('roles/update/{id}', 'Admin\AdminRolesController@update')
    ->name('roles.update');
Route::get('roles/remove/{id?}', 'Admin\AdminRolesController@remove')
    ->name('roles.remove');
Route::get('roles/data', 'Admin\AdminRolesController@rolesData')
    ->name('roles.data');
