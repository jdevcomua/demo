<?php

Route::redirect('/', '/admin/users/index', 302)->name('index');

Route::get('users/index', 'Admin\DataBasesController@userIndex')->name('db.users');

Route::get('users/data', 'Admin\DataBasesController@userData')->name('db.users.data');
