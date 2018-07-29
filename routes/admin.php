<?php

Route::redirect('/', '/admin/animals/index', 302)->name('index');

Route::get('users/index', 'Admin\DataBasesController@userIndex')->name('db.users');
Route::get('users/data', 'Admin\DataBasesController@userData')->name('db.users.data');

Route::get('animals/index', 'Admin\DataBasesController@animalIndex')->name('db.animals');
Route::get('animals/data', 'Admin\DataBasesController@animalData')->name('db.animals.data');
