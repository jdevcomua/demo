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
    Route::put('roles/update/{id?}', 'Admin\DataBasesController@userUpdateRoles')
        ->name('update.roles')
        ->middleware('permission:change-roles');
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
        ->middleware('role:admin');
    Route::get('verify/{id}', 'Admin\DataBasesController@animalVerify')
        ->name('verify')
        ->middleware('permission:verify-animal');
    Route::post('{id}/upload-file', 'Admin\DataBasesController@animalUploadFile')
        ->name('upload-file');
    Route::post('/file/{id}/remove', 'Admin\DataBasesController@animalRemoveFile')
        ->name('remove-file');


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

    Route::get('data/fur', 'Admin\InfoController@directoryDataFur')
        ->name('data.fur');
    Route::post('store/fur', 'Admin\InfoController@directoryStoreFur')
        ->name('store.fur');
    Route::get('remove/fur', 'Admin\InfoController@directoryRemoveFur')
        ->name('remove.fur');
});


Route::group([
    'prefix' => 'emails',
    'as' => 'info.emails.'
], function () {
    Route::get('index', 'Admin\InfoController@emailsIndex')
        ->name('index');
    Route::get('data', 'Admin\InfoController@emailsData')
        ->name('data');
    Route::get('edit/{id?}', 'Admin\InfoController@emailsEdit')
        ->name('edit');
    Route::put('edit/{id}', 'Admin\InfoController@emailsStore')
        ->name('store');
});

Route::group([
    'prefix' => 'notifications',
    'as' => 'info.notifications.'
], function () {
    Route::get('index', 'Admin\InfoController@notificationsIndex')
        ->name('index');
    Route::get('data', 'Admin\InfoController@notificationsData')
        ->name('data');
    Route::put('edit/{id?}', 'Admin\InfoController@notificationsStore')
        ->name('store');
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


Route::group([
    'prefix' => 'administrating',
    'as' => 'administrating.',
    'middleware' => 'role:admin'
], function () {
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        Route::get('/', 'Admin\AdministratingController@users')
            ->name('index');
        Route::get('data', 'Admin\AdministratingController@userData')
            ->name('data');
        Route::post('ban/{id?}', 'Admin\AdministratingController@banUser')
            ->name('ban');
        Route::post('unban/{id?}', 'Admin\AdministratingController@unbanUser')
            ->name('unban');
    });

    Route::get('banned', 'Admin\AdministratingController@bannedUsers')
        ->name('banned');
    Route::get('banned/data', 'Admin\AdministratingController@bannedUsersData')
        ->name('banned.data');


});

Route::group([
    'prefix' => 'roles',
    'as' => 'roles.',
    'middleware' => 'role:admin'
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
    'middleware' => 'role:admin'
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


