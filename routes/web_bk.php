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



Route::group(array('prefix' => 'admin'), function () {
    Route::group(array('middleware' => ['App\Http\Middleware\GuestAdmin', 'PreventBackHistory'], 'namespace' => 'admin'), function () {

        Route::get('', 'AdminLoginController@login')->name('admin.login');
        Route::any('/login', 'AdminLoginController@login')->name('admin.login');
       
    });

    Route::group(array('middleware' => ['App\Http\Middleware\AuthAdmin', 'PreventBackHistory'], 'namespace' => 'admin'), function () {
     
        Route::any('/logout', 'AdminLoginController@logout')->name('admin.logout');
        Route::any('/dashboard', 'AdminDashboardController@showdashboard')->name('admin.dashboard');

        Route::resource('users', 'UsersController');

    });
});