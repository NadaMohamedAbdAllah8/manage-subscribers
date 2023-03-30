<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => '', 'namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::get('login', function () {
        return view('admins.pages.auth.login');
    });

    Route::post('login', 'AuthController@login')
        ->name('login');

    Route::group(['middleware' => 'auth:admin'], function () {

        // logout
        Route::get('logout', 'AuthController@logout')->name('logout');

        /**
         * Subscribers Module Routes
         */
        Route::resource('subscribers', 'SubscriberController')->except(['show']);

        Route::prefix('subscribers')->group(function () {
            Route:: as ('subscribers.')->group(function () {
                Route::get('data', 'SubscriberController@data')->name('data');
            });
        });

    });

});