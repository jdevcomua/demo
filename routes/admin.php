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
        'as' => 'info.content.'
    ], function () {
        Route::get('faq', 'Admin\ContentController@faqIndex')
            ->name('faq.index');
        Route::get('faq/data', 'Admin\ContentController@faqData')
            ->name('faq.data');
        Route::post('faq', 'Admin\ContentController@faqStore')
            ->name('faq.store');
        Route::delete('faq/delete/{id?}', 'Admin\ContentController@faqDelete')
            ->name('faq.delete');

        Route::get('block', 'Admin\ContentController@blockIndex')
            ->name('block.index');
        Route::put('block/{id}/update', 'Admin\ContentController@blockUpdate')
            ->name('block.update');
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
    Route::get('requests', 'Admin\AdministratingController@animalsRequests')
        ->name('requests');
    Route::get('requests/data', 'Admin\AdministratingController@animalsRequestsData')
        ->name('requests.data');
    Route::get('requests/accept/{id?}', 'Admin\AdministratingController@confirmAnimalsRequest')
        ->name('requests.accept');
    Route::get('requests/decline/{id?}', 'Admin\AdministratingController@cancelAnimalsRequest')
        ->name('requests.decline');
    Route::get('requests/proceed/{id?}', 'Admin\AdministratingController@proceedAnimalsRequest')
        ->name('requests.proceed');

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


