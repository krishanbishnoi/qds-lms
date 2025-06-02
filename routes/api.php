<?php



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

Route::group(['namespace' => 'api\v1', 'middleware'   => 'App\Http\Middleware\GuestApi'], function () {

    //customer routing 
    Route::post('login', array('uses' => 'UsersController@Login'));
    Route::post('social-login', array('uses' => 'UsersController@socialLogin'));
    Route::post('sign-up', 'UsersController@Signup');
    Route::post('verify-mobile-otp', 'UsersController@verifyMobileOtp');
    Route::post('verify-email-otp', 'UsersController@verifyEmailOtp');
    Route::post('send-mobile-otp', 'UsersController@SendMobileOtp');
    Route::post('send-email-otp', 'UsersController@SendEmailOtp');

    Route::post('forget-password', array('uses' => 'UsersController@ForgetPassword'));
    Route::post('reset-password', array('uses' => 'UsersController@ResetPassword'));
    Route::get('get-settings', 'UsersController@getSettings');

    Route::post('contact', 'UsersController@ContactUs');
    Route::get('cms-page', 'GlobleUserController@CmsPage');
    Route::post('faqs', 'GlobleUserController@Faqs');
});

Route::group(['namespace' => 'api\v1', 'middleware' => 'jwt.auth'], function () {
    Route::post('logout', 'UsersController@logout');
    //customer routing
    Route::post('change-password', array('uses' => 'UsersController@ChangePassword'));
    Route::post('update-image', 'UsersController@updateImage');

    Route::post('update-profile', 'UsersController@updateProfile');
    Route::get('user-detail', 'UsersController@UserDetail');
    Route::post('edit-email', 'UsersController@editEmail');
    Route::post('update-email', 'UsersController@updateEmail');
    Route::post('edit-mobile-number', 'UsersController@editMobileNumber');
    Route::post('update-mobile-number', 'UsersController@updateMobileNumber');

    Route::post('all-contest', 'ContestsController@AllContest');
    Route::post('contest-details', 'ContestsController@contestDetails');
    Route::get('categories', 'ContestsController@Categories');
    Route::post('update-status', 'ContestsController@updateStatus');

    Route::post('join-contest', 'ContestsController@joinContest');
    Route::post('upcoming-contests', 'ContestsController@upcomingContest');
    Route::post('my-contests', 'ContestsController@myContests');
    Route::post('leave-contest', 'ContestsController@leaveContest');
    Route::post('check-is-joined', 'ContestsController@checkIsjoined');
    Route::post('contest-stocks', 'ContestsController@contestStocks');
    Route::post('all-stocks', 'ContestsController@allStocks');
    Route::post('add-stock-to-user-Portfolio', 'ContestsController@addStockToPortfolio');
    Route::post('user-Portfolio-stocks', 'ContestsController@userPortfolioStocks');
    Route::post('check-contest-budget', 'ContestsController@checkContestBudget');






    Route::post('streek-games', 'ContestsController@streekGames');
    Route::post('streek-participant', 'ContestsController@streekParticipant');

    Route::post('recent-streeks', 'ContestsController@recentStreeks');
    Route::post('won-streeks', 'ContestsController@wonStreeks');
    Route::post('lost-streeks', 'ContestsController@lostStreeks');

    Route::post('select-answer', 'ContestsController@selectAnswer');
    Route::post('valid-answer', 'ContestsController@validAnswer');

    Route::post('transaction-history', 'TransactionController@TransactionHistory');
    Route::post('user-notification', 'GlobleUserController@userNotification');

    Route::post('check-user-exist', 'UsersController@checkUserExist');
    Route::post('streek-won-users', 'ContestsController@streekWiners');
    Route::post('select-rendom-answer', 'ContestsController@selectRendomAnswer');

    Route::post('withdraw-payment', 'ContestsController@withdrawPayment');
    Route::post('knockout', 'ContestsController@knockout');
});
