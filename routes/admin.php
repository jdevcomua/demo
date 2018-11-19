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
    Route::put('update/{id}', 'Admin\DataBasesController@userUpdate')
        ->name('update')
        ->middleware('permission:edit-user');

    Route::put('update/{id}/address', 'Admin\DataBasesController@userUpdateAddress')
        ->name('update.address')
        ->middleware('permission:edit-user');

    Route::put('roles/update/{id?}', 'Admin\DataBasesController@userUpdateRoles')
        ->name('update.roles')
        ->middleware('permission:change-roles');
    Route::delete('delete/{id?}', 'Admin\DataBasesController@userDelete')
        ->name('remove')
        ->middleware('permission:delete-user');

    Route::get('animals/{id?}', 'Admin\DataBasesController@userShowAnimals')
        ->name('animals');
});

Route::group([
    'prefix' => 'animals',
    'as' => 'db.animals.'
], function () {
    Route::get('index', 'Admin\DataBasesController@animalIndex')
        ->name('index');
    Route::get('data/{id?}', 'Admin\DataBasesController@animalData')
        ->name('data');
    Route::get('create/{id?}', 'Admin\DataBasesController@animalCreate')
        ->name('create');
    Route::post('store', 'Admin\DataBasesController@animalStore')
        ->name('store');
    Route::get('edit/{id?}', 'Admin\DataBasesController@animalEdit')
        ->name('edit');
    Route::put('update/{id}', 'Admin\DataBasesController@animalUpdate')
        ->name('update');
    Route::delete('remove/{id?}', 'Admin\DataBasesController@animalRemove')
        ->name('remove')
        ->middleware('permission:delete-animal');
    Route::get('verify/{id}', 'Admin\DataBasesController@animalVerify')
        ->name('verify')
        ->middleware('permission:verify-animal');
    Route::post('{id}/upload-file', 'Admin\DataBasesController@animalUploadFile')
        ->name('upload-file');
    Route::post('/file/{id}/remove', 'Admin\DataBasesController@animalRemoveFile')
        ->name('remove-file');
});

Route::group([
    'middleware' => 'permission:edit-content'
], function () {

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
        Route::post('update/breed', 'Admin\InfoController@directoryUpdateBreed')
            ->name('update.breed');
        Route::get('remove/breed', 'Admin\InfoController@directoryRemoveBreed')
            ->name('remove.breed');

        Route::get('data/color', 'Admin\InfoController@directoryDataColor')
            ->name('data.color');
        Route::post('store/color', 'Admin\InfoController@directoryStoreColor')
            ->name('store.color');
        Route::post('update/color', 'Admin\InfoController@directoryUpdateColor')
            ->name('update.color');
        Route::get('remove/color', 'Admin\InfoController@directoryRemoveColor')
            ->name('remove.color');

        Route::get('data/fur', 'Admin\InfoController@directoryDataFur')
            ->name('data.fur');
        Route::post('store/fur', 'Admin\InfoController@directoryStoreFur')
            ->name('store.fur');
        Route::post('update/fur', 'Admin\InfoController@directoryUpdateFur')
            ->name('update.fur');
        Route::get('remove/fur', 'Admin\InfoController@directoryRemoveFur')
            ->name('remove.fur');

        Route::get('data/cause-of-death', 'Admin\InfoController@directoryDataCauseOfDeath')
            ->name('data.cause-of-death');
        Route::post('store/cause-of-death', 'Admin\InfoController@directoryStoreCauseOfDeath')
            ->name('store.cause-of-death');
        Route::post('update/cause-of-death', 'Admin\InfoController@directoryUpdateCauseOfDeath')
            ->name('update.cause-of-death');
        Route::get('remove/cause-of-death', 'Admin\InfoController@directoryRemoveCauseOfDeath')
            ->name('remove.cause-of-death');
    });

    Route::group([
        'prefix' => 'notifications',
        'as' => 'info.notifications.'
    ], function () {
        Route::get('index', 'Admin\InfoController@notificationsIndex')
            ->name('index');
        Route::get('data', 'Admin\InfoController@notificationsData')
            ->name('data');
        Route::get('create', 'Admin\InfoController@notificationsCreate')
            ->name('create');
        Route::post('store', 'Admin\InfoController@notificationsStore')
            ->name('store');
        Route::get('edit/{id?}', 'Admin\InfoController@notificationsEdit')
            ->name('edit');
        Route::put('update/{id}', 'Admin\InfoController@notificationsUpdate')
            ->name('update');
        Route::delete('delete/{id?}', 'Admin\InfoController@notificationsDestroy')
            ->name('delete');
    });

    Route::group([
        'prefix' => 'content',
        'as' => 'content.'
    ], function () {

        Route::group([
            'prefix' => 'faq',
            'as' => 'faq.',
            'namespace' => 'Admin\Content'
        ], function () {
            Route::get('/', 'FaqController@index')->name('index');
            Route::get('data', 'FaqController@data')->name('data');
            Route::post('/', 'FaqController@store')->name('store');
            Route::get('/{id}', 'FaqController@show')->name('show');
            Route::put('/{id}', 'FaqController@update')->name('update');
            Route::delete('/{id?}', 'FaqController@destroy')->name('delete');
            Route::get('/move-up/{id?}', 'FaqController@moveUp')->name('move-up');
            Route::get('/move-down/{id?}', 'FaqController@moveDown')->name('move-down');
        });

        Route::group([
            'prefix' => 'block',
            'as' => 'block.',
            'namespace' => 'Admin\Content'
        ], function () {
            Route::get('/', 'BlockController@index')->name('index');
            Route::put('{id}/update', 'BlockController@update')->name('update');
        });
    });

});


Route::group([
    'prefix' => 'administrating',
    'as' => 'administrating.',
], function () {
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        Route::get('/', 'Admin\AdministratingController@users')
            ->name('index');
        Route::get('data', 'Admin\AdministratingController@userData')
            ->name('data');
    });

    Route::group([
        'middleware' => 'permission:block-user'
    ], function () {

        Route::post('users/ban/{id?}', 'Admin\AdministratingController@banUser')
            ->name('users.ban');
        Route::post('users/unban/{id?}', 'Admin\AdministratingController@unbanUser')
            ->name('users.unban');

        Route::get('banned', 'Admin\AdministratingController@bannedUsers')
            ->name('banned');
        Route::get('banned/data', 'Admin\AdministratingController@bannedUsersData')
            ->name('banned.data');

    });

    Route::group([
        'prefix' => 'requests',
        'as' => 'requests.'
    ], function () {

        Route::group([
            'prefix' => 'own',
            'as' => 'own.'
        ], function () {
            Route::get('/', 'Admin\Requests\OwnRequestsController@index')
                ->name('index');
            Route::get('/data', 'Admin\Requests\OwnRequestsController@data')
                ->name('data');
            Route::get('/accept/{id?}', 'Admin\Requests\OwnRequestsController@confirm')
                ->name('accept');
            Route::get('/decline/{id?}', 'Admin\Requests\OwnRequestsController@cancel')
                ->name('decline');
            Route::get('/proceed/{id?}', 'Admin\Requests\OwnRequestsController@proceed')
                ->name('proceed');
        });

        Route::group([
            'prefix' => 'lost',
            'as' => 'lost.'
        ], function () {
            Route::get('/', 'Admin\Requests\LostRequestsController@index')
                ->name('index');
            Route::get('data', 'Admin\Requests\LostRequestsController@data')
                ->name('data');
            Route::get('/proceed/{id?}', 'Admin\Requests\LostRequestsController@proceed')
                ->name('proceed');
        });

        Route::group([
            'prefix' => 'change-own',
            'as' => 'change-own.'
        ], function () {
            Route::get('/', 'Admin\Requests\ChangeOwnRequestsController@index')
                ->name('index');
            Route::get('data', 'Admin\Requests\ChangeOwnRequestsController@data')
                ->name('data');
            Route::get('/proceed/{id?}', 'Admin\Requests\ChangeOwnRequestsController@proceed')
                ->name('proceed');
        });

    });

});

Route::group([
    'prefix' => 'roles',
    'as' => 'roles.',
    'middleware' => 'permission:edit-roles'
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

Route::group([
    'prefix' => 'logs',
    'as' => 'logs.',
    'middleware' => 'permission:view-syslog'
], function () {

    Route::get('/', 'Admin\LogsController@index')
        ->name('index');
    Route::get('/data', 'Admin\LogsController@data')
        ->name('data');
    Route::get('/{id?}', 'Admin\LogsController@show')
        ->name('show');

});

Route::group([
    'middleware' => 'role:admin'
], function () {

    Route::get('/object/{type?}/{id?}', 'Admin\AdministratingController@object')
        ->name('object');

});


