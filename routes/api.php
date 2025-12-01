<?php

use App\Http\Controllers\api\RetailTrainingController;
use Illuminate\Http\Request;


/*

|----------------------- ---------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {});
Route::controller(RetailTrainingController::class)->group(function () {
    Route::post('get-training', 'getTrainings');
    Route::post('get-user-training', 'userAudit');
    // get complated and pendid training for view data
    Route::post('get-all-trainings', 'userTrainingDetails');
    Route::post('get-training-url', 'getTrainingUrl');
});

Route::group(['namespace' => 'api\v1', 'middleware'   => 'App\Http\Middleware\GuestApi'], function () {
    //customer routing 
    Route::post('login', array('uses' => 'UsersController@Login'));
    Route::post('/forgot-password', 'UsersController@apiForgotPassword');
    Route::post('/reset-password', 'UsersController@apiResetPassword');
});
