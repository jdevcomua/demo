<?php

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

    Route::group(['middleware' => 'auth'], function () {

        Route::resource('animals', 'AnimalsController');
        Route::post('animals/search-request', 'AnimalsController@findAnimalRequest')->name('animals.search-request');
        Route::post('/animals/file/{animalFile}/remove', 'AnimalsController@removeFile')->name('animals.remove-file');
        Route::view('/animals/verify', 'animals.verify')->name('animals.verify');

        Route::get('/profile', 'ProfileController@show')->name('profile');
        Route::post('/profile', 'ProfileController@update')->name('profile.update');

    });

    Route::group([
        'prefix' => '/ajax',
        'as' => 'ajax.',
        'middleware' => ['auth'],
    ], function () {

        Route::get('/species/{species}/breeds', 'AjaxController@getBreeds')
            ->name('getBreeds');
        Route::get('/species/{species}/colors', 'AjaxController@getColors')
            ->name('getColors');
        Route::get('/species/{species}/furs', 'AjaxController@getFurs')
            ->name('getFurs');
        Route::get('/users', 'AjaxController@getUsers')
            ->name('getUsers');
        Route::post('/search', 'AjaxController@badgeSearch')
            ->name('animals.search');
        Route::post('/animal/request', 'AjaxController@requestAnimal')
            ->name('animals.request');
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
Route::get('/login/as/user', 'AuthController@loginAsUser');

Route::get('/test', function() {
    $user = Auth::user();
    $user->notify(new \App\Notifications\SystemNotification('brrrrrrrr'));
});

Route::get('/test2', function() {
    $user = Auth::user();
    dd($user, $user->notifications);
});

Route::get('/test3', function() {
//    $users = \App\User::all();
//    $t1 = microtime(true);
//    Notification::send($users, new \App\Notifications\SystemNotification('brrrrrrrr'));
//    $t2 = microtime(true);
//
//    dd($t2 - $t1);

    $selector = new \Illuminate\Translation\MessageSelector();
    $loc = config('app.locale');

    dd($loc, $selector->choose('тварина|тварини|тварин', 0, $loc));
});

Route::get('/test4', function() {
    dd(
        app('rha_events')
    );
});
