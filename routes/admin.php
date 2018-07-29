<?php

Route::redirect('/', '/admin/animals/index', 302)
    ->name('index');



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

Route::get('directories/data/color', 'Admin\InfoController@directoryDataColor')
    ->name('info.directories.data.color');
Route::post('directories/store/color', 'Admin\InfoController@directoryStoreColor')
    ->name('info.directories.store.color');


