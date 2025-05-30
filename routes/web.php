<?php

// use Illuminate\Support\Facades\Route;

// DB::enableQueryLog();
include(app_path() . '/global_constants.php');
include(app_path() . '/settings.php');
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
// Route::post('/update-device-token', 'WebNotificationController@storeToken');
// Route::get('/send-web-notification', 'WebNotificationController@sendWebNotification');


//crons url
Route::get('crone/move-status-to-publish', 'CroneController@moveStatusToPublish');
//Route::get('crone/move-status-to-active','CroneController@moveStatusToActive');//streeks crone
// Route::get('crone/change-game-status','CroneController@moveStatusToGameEnd');
// Route::get('crone/change-game-status-to-start','CroneController@moveStatusToGameStart');

// Route::get('crone/end-streek','CroneController@endStreek');
// Route::get('crone/won-streek','CroneController@wonStreek');
// Route::get('crone/update-rank','CroneController@updateRank');
// Route::get('crone/knockout','CroneController@knockout');
// Route::get('crone/check-balance','CroneController@sendNotificationUserBelowBalance');
// Route::get('crone/send-notfi-to-inform-joinstreek','CroneController@sendNotificationUserToInfornToJoin');
// Route::get('crone/update-rank','CroneController@rank');

// Route::get('/', function () {
//     return view('welcome');
// });

################################################################# Admin Routing start here###################################################
Route::group(array('prefix' => 'admin'), function () {
    Route::group(array('middleware' => ['App\Http\Middleware\GuestAdmin', 'PreventBackHistory'], 'namespace' => 'admin'), function () {


        Route::get('', 'AdminLoginController@login');
        Route::any('/login', 'AdminLoginController@login');
        Route::get('forget_password', 'AdminLoginController@forgetPassword');
        Route::get('reset_password/{validstring}', 'AdminLoginController@resetPassword');
        Route::post('send_password', 'AdminLoginController@sendPassword');
        Route::post('save_password', 'AdminLoginController@resetPasswordSave');
    });

    Route::group(array('middleware' => ['App\Http\Middleware\AuthAdmin', 'PreventBackHistory'], 'namespace' => 'admin'), function () {
        Route::get('/logout', 'AdminLoginController@logout');
        Route::get('/dashboard', array('as' => 'dashboard', 'uses' => 'AdminDashboardController@showdashboard'));
        Route::get('/myaccount', 'AdminDashboardController@myaccount');
        Route::post('/myaccount', 'AdminDashboardController@myaccountUpdate');
        Route::get('/bankdetail', 'AdminDashboardController@bankdetail');
        Route::post('/bankdetail', 'AdminDashboardController@bankdetailUpdate');
        Route::get('/change-password', 'AdminDashboardController@change_password');
        Route::post('/changed-password', 'AdminDashboardController@changedPassword');



        /* user routes */
        Route::get('training-managers', array('as' => 'Users.index', 'uses' => 'UsersController@index'));
        Route::post('training-managers', array('as' => 'Users.index', 'uses' => 'UsersController@index'));
        Route::get('training-managers/add-new-training-managers', array('as' => 'Users.add', 'uses' => 'UsersController@add'));
        Route::post('training-managers/add-new-training-managers', array('as' => 'Users.add', 'uses' => 'UsersController@save'));
        Route::get('training-managers/edit-training-managers/{id}', array('as' => 'Users.edit', 'uses' => 'UsersController@edit'));
        Route::post('training-managers/edit-training-managers/{id}', array('as' => 'Users.edit', 'uses' => 'UsersController@update'));
        Route::get('training-managers/delete-training-managers/{id}', array('as' => 'Users.delete', 'uses' => 'UsersController@delete'));
        Route::get('training-managers/view-training-managers/{id}', array('as' => 'Users.view', 'uses' => 'UsersController@view'));
        Route::get('training-managers/update-training-managers-status/{id}/{status}', array('as' => 'Users.status', 'uses' => 'UsersController@changeStatus'));
        Route::post('training-managers/import-training-managers', 'UsersController@importUsers')->name('import.training-managers');


        /* user routes */
        // Route::get('users', array('as' => 'Users.index', 'uses' => 'UsersController@index'));
        // Route::post('users', array('as' => 'Users.index', 'uses' => 'UsersController@index'));
        // Route::get('users/add-new-user', array('as' => 'Users.add', 'uses' => 'UsersController@add'));
        // Route::post('users/add-new-user', array('as' => 'Users.add', 'uses' => 'UsersController@save'));
        // Route::get('users/edit-user/{id}', array('as' => 'Users.edit', 'uses' => 'UsersController@edit'));
        // Route::post('users/edit-user/{id}', array('as' => 'Users.edit', 'uses' => 'UsersController@update'));
        // Route::get('users/delete-user/{id}', array('as' => 'Users.delete', 'uses' => 'UsersController@delete'));
        // Route::get('users/view-user/{id}', array('as' => 'Users.view', 'uses' => 'UsersController@view'));
        // Route::get('users/update-user-status/{id}/{status}', array('as' => 'Users.status', 'uses' => 'UsersController@changeStatus'));
        // Route::post('users/import-users', 'UsersController@importUsers')->name('import.users');

        /* user routes */
        Route::get('trainers', array('as' => 'Trainers.index', 'uses' => 'TrainersController@index'));
        Route::post('trainers', array('as' => 'Trainers.index', 'uses' => 'TrainersController@index'));
        Route::get('trainers/add-new-trainer', array('as' => 'Trainers.add', 'uses' => 'TrainersController@add'));
        Route::post('trainers/add-new-trainer', array('as' => 'Trainers.add', 'uses' => 'TrainersController@save'));
        Route::get('trainers/edit-trainer/{id}', array('as' => 'Trainers.edit', 'uses' => 'TrainersController@edit'));
        Route::post('trainers/edit-trainer/{id}', array('as' => 'Trainers.edit', 'uses' => 'TrainersController@update'));
        Route::get('trainers/delete-trainer/{id}', array('as' => 'Trainers.delete', 'uses' => 'TrainersController@delete'));
        Route::get('trainers/view-trainer/{id}', array('as' => 'Trainers.view', 'uses' => 'TrainersController@view'));
        Route::get('trainers/update-trainer-status/{id}/{status}', array('as' => 'Trainers.status', 'uses' => 'TrainersController@changeStatus'));
        Route::post('trainers/import-trainers', 'TrainersController@importTrainers')->name('import.trainers');
        Route::get('trainers/export-trainers', 'TrainersController@exportTrainers')->name('export.trainers');


        /* user routes */
        Route::get('users', array('as' => 'Trainees.index', 'uses' => 'TraineesController@index'));
        Route::post('users', array('as' => 'Trainees.index', 'uses' => 'TraineesController@index'));
        Route::get('users/add-new-user', array('as' => 'Trainees.add', 'uses' => 'TraineesController@add'));
        Route::post('users/add-new-user', array('as' => 'Trainees.add', 'uses' => 'TraineesController@save'));
        Route::get('users/edit-user/{id}', array('as' => 'Trainees.edit', 'uses' => 'TraineesController@edit'));
        Route::post('users/edit-user/{id}', array('as' => 'Trainees.edit', 'uses' => 'TraineesController@update'));
        Route::get('users/delete-user/{id}', array('as' => 'Trainees.delete', 'uses' => 'TraineesController@delete'));
        Route::get('users/view-user/{id}', array('as' => 'Trainees.view', 'uses' => 'TraineesController@view'));
        Route::get('users/update-user-status/{id}/{status}', array('as' => 'Trainees.status', 'uses' => 'TraineesController@changeStatus'));
        Route::post('trainees/import-trainees', 'TraineesController@importTrainees')->name('import.trainees');
        Route::get('trainees/export-trainees', 'TraineesController@exportTrainees')->name('export.trainees');
        Route::get('trainees/export-all-trainees', 'TraineesController@exportTraineesAll')->name('export.trainees.all');

        Route::post('users/import-change-designation', 'UsersController@importChangeDesignation')->name('import.change.designation');

        Route::post('users/change-designation', array('as' => 'Trainees.ChangeDesignation', 'uses' => 'TraineesController@ChangeDesignation'));
        Route::post('users/delete-multiple', array('as' => 'Trainees.DeleteMultiple', 'uses' => 'TraineesController@DeleteMultiple'));

        Route::get('users/report/{id}', array('as' => 'Trainees.report', 'uses' => 'TraineesController@traineeWiseReport'));
        Route::get('users/{user_id}/test/{test_id}/report', array('as' => 'Trainees.Test.report', 'uses' => 'TraineesController@traineeTestWiseReport'));

        /* user routes */

        /** settings routing**/
        Route::get('trainings', array('as' => 'Training.index', 'uses' => 'TrainingController@index'));
        Route::post('trainings', array('as' => 'Training.index', 'uses' => 'TrainingController@index'));
        Route::get('trainings/add-new-training', array('as' => 'Training.add', 'uses' => 'TrainingController@add'));
        Route::post('trainings/add-new-training', array('as' => 'Training.add', 'uses' => 'TrainingController@save'));
        Route::get('trainings/edit-training/{id}', array('as' => 'Training.edit', 'uses' => 'TrainingController@edit'));
        Route::post('trainings/edit-training/{id}', array('as' => 'Training.edit', 'uses' => 'TrainingController@update'));
        Route::get('trainings/delete-training/{id}', array('as' => 'Training.delete', 'uses' => 'TrainingController@delete'));
        Route::get('trainings/view-training/{id}', array('as' => 'Training.view', 'uses' => 'TrainingController@view'));
        Route::get('trainings/update-training-status/{id}/{status}', array('as' => 'Training.status', 'uses' => 'TrainingController@changeStatus'));

        Route::get('training/export-tainings', 'TrainingController@exportTraining')->name('export.training');

        Route::post('trainings/import-questions/{test_id}', 'QuestionController@importQuestions')->name('import.questions');
        Route::get('/download-sample-file-questions', 'QuestionController@downloadQuestionSample')->name('download.sample.file.questions');

        Route::get('training/import-training-participants/{id}', 'TrainingController@importTrainingParticipants')->name('import.importTrainingParticipants');
        Route::post('training/import-training-participants/{id}', 'TrainingController@importTraining')->name('import.training-participants');

        /* Ai Create Trainings routes */
        Route::get('trainings/add-new-training-with-ai', 'TrainingController@addAi')->name('training.add.ai');
        Route::post('trainings/add-new-training-with-ai', 'TrainingController@saveAi')->name('training.submit.ai');

        /* training category modules routes */
        Route::get('trainings/category', array('as' => 'TrainingCategory.index', 'uses' => 'TrainingCategoryController@index'));
        Route::post('trainings/category', array('as' => 'TrainingCategory.index', 'uses' => 'TrainingCategoryController@index'));
        Route::get('trainings/add-category', array('as' => 'TrainingCategory.add', 'uses' => 'TrainingCategoryController@add'));
        Route::post('trainings/add-category', array('as' => 'TrainingCategory.add', 'uses' => 'TrainingCategoryController@save'));
        Route::get('trainings/edit-category/{id}', array('as' => 'TrainingCategory.edit', 'uses' => 'TrainingCategoryController@edit'));
        Route::post('trainings/edit-category/{id}', array('as' => 'TrainingCategory.edit', 'uses' => 'TrainingCategoryController@update'));
        Route::get('trainings/delete-category/{id}', array('as' => 'TrainingCategory.delete', 'uses' => 'TrainingCategoryController@delete'));
        Route::get('trainings/view-category/{id}', array('as' => 'TrainingCategory.view', 'uses' => 'TrainingCategoryController@view'));
        // Route::get('trainings/update-training-status/{id}/{status}', array('as' => 'TrainingCategory.status', 'uses' => 'TrainingController@changeStatus'));

        /* course modules routes */
        Route::get('trainings/courses/{training_id}', array('as' => 'Course.index', 'uses' => 'CoursesController@index'));
        Route::post('trainings/courses/{training_id}', array('as' => 'Course.index', 'uses' => 'CoursesController@index'));
        Route::get('trainings/courses/add-new-course/{training_id}', array('as' => 'Course.add', 'uses' => 'CoursesController@add'));
        Route::post('trainings/courses/add-new-course/{training_id}', array('as' => 'Course.add', 'uses' => 'CoursesController@save'));
        Route::get('trainings/courses/edit-course/{training_id}/{id}', array('as' => 'Course.edit', 'uses' => 'CoursesController@edit'));
        Route::post('trainings/courses/edit-course/{training_id}/{id}', array('as' => 'Course.edit', 'uses' => 'CoursesController@update'));
        Route::get('trainings/courses/delete-course/{id}', array('as' => 'Course.delete', 'uses' => 'CoursesController@delete'));
        Route::get('trainings/courses/view-course/{training_id}/{id}', array('as' => 'Course.view', 'uses' => 'CoursesController@view'));
        Route::get('trainings/courses/update-course-status/{id}/{status}', array('as' => 'Course.status', 'uses' => 'CoursesController@changeStatus'));
        Route::post('courses/add-more-document', array('as' => 'Course.addMoreDocument', 'uses', 'uses' => 'CoursesController@addMoreDocument'));
        Route::post('courses/delete-more-document', array('as' => 'Course.deleteMoreDocument', 'uses', 'uses' => 'CoursesController@deleteMoreDocument'));
        Route::post('trainings/assign-manager', array('as' => 'Training.AssignManager', 'uses' => 'TrainingController@AssignManager'));
        Route::post('trainings/assign-trainer', array('as' => 'Training.AssignTrainer', 'uses' => 'TrainingController@AssignTrainer'));



        /* test category modules routes */
        Route::get('tests/category', array('as' => 'TestCategory.index', 'uses' => 'TestCategoryController@index'));
        Route::post('tests/category', array('as' => 'TestCategory.index', 'uses' => 'TestCategoryController@index'));
        Route::get('tests/add-category', array('as' => 'TestCategory.add', 'uses' => 'TestCategoryController@add'));
        Route::post('tests/add-category', array('as' => 'TestCategory.add', 'uses' => 'TestCategoryController@save'));
        Route::get('tests/edit-category/{id}', array('as' => 'TestCategory.edit', 'uses' => 'TestCategoryController@edit'));
        Route::post('tests/edit-category/{id}', array('as' => 'TestCategory.edit', 'uses' => 'TestCategoryController@update'));
        Route::get('tests/delete-category/{id}', array('as' => 'TestCategory.delete', 'uses' => 'TestCategoryController@delete'));
        Route::get('tests/view-category/{id}', array('as' => 'TestCategory.view', 'uses' => 'TestCategoryController@view'));

        /** test routing**/
        Route::get('tests', array('as' => 'Test.index', 'uses' => 'TestController@index'));
        Route::post('tests', array('as' => 'Test.index', 'uses' => 'TestController@index'));
        Route::get('tests/add-new-test', array('as' => 'Test.add', 'uses' => 'TestController@add'));
        Route::post('tests/add-new-test', array('as' => 'Test.add', 'uses' => 'TestController@save'));
        Route::get('tests/edit-test/{id}', array('as' => 'Test.edit', 'uses' => 'TestController@edit'));
        Route::post('tests/edit-test/{id}', array('as' => 'Test.edit', 'uses' => 'TestController@update'));
        Route::get('tests/delete-test/{id}', array('as' => 'Test.delete', 'uses' => 'TestController@delete'));
        Route::get('tests/view-test/{id}', array('as' => 'Test.view', 'uses' => 'TestController@view'));
        Route::get('tests/update-test-status/{id}/{status}', array('as' => 'Test.status', 'uses' => 'TestController@changeStatus'));
        Route::get('tests/export-tests', 'TestController@exportTests')->name('export.tests');
        Route::get('tests/import-tests-participants/{id}', 'TestController@importTestsParticipants')->name('import.importregularTestsParticipants');
        Route::post('tests/import-tests-participants/{id}', 'TestController@importTests')->name('import.tests');
        Route::post('tests/assign-manager', array('as' => 'Test.AssignManager', 'uses' => 'TestController@AssignManager'));
        Route::post('tests/assign-trainer', array('as' => 'Test.AssignTrainer', 'uses' => 'TestController@AssignTrainer'));

        // option to add participate directly not with Excel upload
        Route::post('tests/import-tests-participants-user/{id}', 'TestController@importTestsUsersDirectly')->name('import.tests.usersDirectly');

        /* test questions routes */
        Route::get('tests/questions/{test_id}', array('as' => 'Question.index', 'uses' => 'QuestionController@index'));
        Route::post('tests/questions/{test_id}', array('as' => 'Question.index', 'uses' => 'QuestionController@index'));
        Route::get('tests/questions/add-new-question/{test_id}', array('as' => 'Question.add', 'uses' => 'QuestionController@add'));
        Route::post('tests/questions/add-new-question/{test_id}', array('as' => 'Question.add', 'uses' => 'QuestionController@save'));
        Route::get('tests/questions/edit-question/{test_id}/{id}', array('as' => 'Question.edit', 'uses' => 'QuestionController@edit'));
        Route::post('tests/questions/edit-question/{test_id}/{id}', array('as' => 'Question.edit', 'uses' => 'QuestionController@update'));
        Route::get('tests/questions/delete-question/{id}', array('as' => 'Question.delete', 'uses' => 'QuestionController@delete'));
        Route::get('tests/questions/view-question/{test_id}/{id}', array('as' => 'Question.view', 'uses' => 'QuestionController@view'));
        Route::get('tests/questions/update-question-status/{id}/{status}', array('as' => 'Question.status', 'uses' => 'QuestionController@changeStatus'));
        Route::post('questions/add-more-option', array('as' => 'Contests.addMoreOption', 'uses', 'uses' => 'QuestionController@addMoreOption'));
        Route::post('questions/delete-more-option', array('as' => 'Contests.deleteMoreOption', 'uses', 'uses' => 'QuestionController@deleteMoreOption'));
        /* test report routes */
        Route::get('test/{test_id}', array('as' => 'Test.report', 'uses' => 'TestController@testReport'));

        /* Feedback modules routes */
        Route::get('tests/feedback', array('as' => 'Feedback.index', 'uses' => 'FeedbackController@index'));
        Route::post('tests/feedback', array('as' => 'Feedback.index', 'uses' => 'FeedbackController@index'));
        Route::get('tests/feedback/add', array('as' => 'Feedback.add', 'uses' => 'FeedbackController@add'));
        Route::post('tests/feedback/add', array('as' => 'Feedback.add', 'uses' => 'FeedbackController@save'));
        Route::get('tests/feedback/edit-category/{id}', array('as' => 'Feedback.edit', 'uses' => 'FeedbackController@edit'));
        Route::post('tests/feedback/edit-category/{id}', array('as' => 'Feedback.edit', 'uses' => 'FeedbackController@update'));
        Route::get('tests/feedback/delete-category/{id}', array('as' => 'Feedback.delete', 'uses' => 'FeedbackController@delete'));
        Route::get('tests/feedback/view-category/{id}', array('as' => 'Feedback.view', 'uses' => 'FeedbackController@view'));
        Route::get('tests/feedback/update-test-status/{id}/{status}', array('as' => 'Feedback.status', 'uses' => 'FeedbackController@changeStatus'));
        Route::get('tests/feedback/export-tests', 'FeedbackController@exportTests')->name('export.tests');
        Route::get('tests/feedback/import-tests-participants/{id}', 'FeedbackController@importTestsParticipants')->name('import.importTestsParticipants');
        Route::post('tests/feedback/import-tests-participants/{id}', 'FeedbackController@importTests')->name('import.tests');
        Route::post('tests/feedback/assign-manager', array('as' => 'Feedback.AssignManager', 'uses' => 'FeedbackController@AssignManager'));
        Route::post('tests/feedback/assign-trainer', array('as' => 'Feedback.AssignTrainer', 'uses' => 'FeedbackController@AssignTrainer'));
        // option to add participate in feedback test directly not with Excel upload
        Route::post('tests/feedback/import-tests-participants-user/{id}', 'FeedbackController@importTestsUsersDirectly')->name('import.feedbackTest.usersDirectly');

        /* user routes */
        Route::get('sub-admin', array('as' => 'SubAdmin.index', 'uses' => 'SubAdminController@index'));
        Route::post('sub-admin', array('as' => 'SubAdmin.index', 'uses' => 'SubAdminController@index'));
        Route::get('sub-admin/add-new-admin', array('as' => 'SubAdmin.add', 'uses' => 'SubAdminController@add'));
        Route::post('sub-admin/add-new-admin', array('as' => 'SubAdmin.add', 'uses' => 'SubAdminController@save'));
        Route::get('sub-admin/edit-admin/{id}', array('as' => 'SubAdmin.edit', 'uses' => 'SubAdminController@edit'));
        Route::post('sub-admin/edit-admin/{id}', array('as' => 'SubAdmin.edit', 'uses' => 'SubAdminController@update'));
        Route::get('sub-admin/delete-admin/{id}', array('as' => 'SubAdmin.delete', 'uses' => 'SubAdminController@delete'));
        Route::get('sub-admin/view-admin/{id}', array('as' => 'SubAdmin.view', 'uses' => 'SubAdminController@view'));
        Route::get('sub-admin/update-admin-status/{id}/{status}', array('as' => 'SubAdmin.status', 'uses' => 'SubAdminController@changeStatus'));

        /** cms-manager routing**/
        Route::any('/cms-manager', array('as' => 'Cms.index', 'uses' => 'CmspagesController@listCms'));
        Route::get('cms-manager/add-cms', 'CmspagesController@addCms');
        Route::post('cms-manager/add-cms', 'CmspagesController@saveCms');
        Route::get('cms-manager/edit-cms/{id}', 'CmspagesController@editCms');
        Route::post('cms-manager/edit-cms/{id}', 'CmspagesController@updateCms');
        Route::get('cms-manager/update-status/{id}/{status}', 'CmspagesController@updateCmsStatus');

        /** email-manager routing**/
        Route::get('/email-manager', array('as' => 'EmailTemplate.index', 'uses' => 'EmailtemplateController@listTemplate'));
        Route::get('/email-manager/add-template', array('as' => 'EmailTemplate.add', 'uses' => 'EmailtemplateController@addTemplate'));
        Route::post('/email-manager/add-template', 'EmailtemplateController@saveTemplate');
        Route::get('/email-manager/edit-template/{id}', array('as' => 'EmailTemplate.edit', 'uses' => 'EmailtemplateController@editTemplate'));
        Route::post('/email-manager/edit-template/{id}', 'EmailtemplateController@updateTemplate');
        Route::post('/email-manager/get-constant', 'EmailtemplateController@getConstant');

        ### Email Logs Manager routing
        Route::get('/email-logs', array('as' => 'EmailLogs.listEmail', 'uses' => 'EmailLogsController@listEmail'));
        Route::any('/email-logs/email_details/{id}', 'EmailLogsController@EmailDetail');
        /** email-manager routing**/





        ### contact manager routing
        Route::any('contact-manager', array('as' => 'Contact.index', 'uses' => 'ContactsController@listContact'));
        Route::get('contact-manager/view-contact/{id}', array('as' => 'Contact.view', 'uses' => 'ContactsController@viewContact'));
        Route::any('contact-manager/reply-to-user/{id}', array('as' => 'Contact.reply', 'uses' => 'ContactsController@replyToUser'));
        Route::get('contact-manager/delete-contact/{id}', array('as' => 'Contact.delete', 'uses' => 'ContactsController@delete'));
        ### contact manager routing


        /** settings routing**/
        Route::any('/settings', array('as' => 'settings.listSetting', 'uses' => 'SettingsController@listSetting'));
        Route::get('/settings/add-setting', array('as' => 'settings.add', 'uses' => 'SettingsController@addSetting'));
        Route::post('/settings/add-setting', array('as' => 'settings.add', 'uses' => 'SettingsController@saveSetting'));
        Route::get('/settings/edit-setting/{id}', array('as' => 'settings.edit', 'uses' => 'SettingsController@editSetting'));
        Route::post('/settings/edit-setting/{id}', array('as' => 'settings.edit', 'uses' => 'SettingsController@updateSetting'));
        Route::get('/settings/prefix/{slug}', array('as' => 'settings.prefix', 'uses' => 'SettingsController@prefix'));
        Route::post('/settings/prefix/{slug}', array('as' => 'settings.prefix', 'uses' => 'SettingsController@updatePrefix'));
        Route::get('/settings/delete-setting/{id}', array('as' => 'settings.delete', 'uses' => 'SettingsController@deleteSetting'));


        /** settings routing**/
        Route::get('masters/circles', array('as' => 'Circle.index', 'uses' => 'CircleController@index'));
        Route::post('masters/circles', array('as' => 'Circle.index', 'uses' => 'CircleController@index'));
        Route::get('masters/circles/add-new-circle', array('as' => 'Circle.add', 'uses' => 'CircleController@add'));
        Route::post('masters/circles/add-new-circle', array('as' => 'Circle.add', 'uses' => 'CircleController@save'));
        Route::get('masters/circles/edit-circle/{id}', array('as' => 'Circle.edit', 'uses' => 'CircleController@edit'));
        Route::post('masters/circles/edit-circle/{id}', array('as' => 'Circle.edit', 'uses' => 'CircleController@update'));
        Route::get('masters/circles/delete-circle/{id}', array('as' => 'Circle.delete', 'uses' => 'CircleController@delete'));
        Route::get('masters/circles/view-circle/{id}', array('as' => 'Circle.view', 'uses' => 'CircleController@view'));
        Route::get('masters/circles/update-circle-status/{id}/{status}', array('as' => 'Circle.status', 'uses' => 'CircleController@changeStatus'));

        /** settings routing**/
        Route::get('masters/regions', array('as' => 'Region.index', 'uses' => 'RegionController@index'));
        Route::post('masters/regions', array('as' => 'Region.index', 'uses' => 'RegionController@index'));
        Route::get('masters/regions/add-new-region', array('as' => 'Region.add', 'uses' => 'RegionController@add'));
        Route::post('masters/regions/add-new-region', array('as' => 'Region.add', 'uses' => 'RegionController@save'));
        Route::get('masters/regions/edit-region/{id}', array('as' => 'Region.edit', 'uses' => 'RegionController@edit'));
        Route::post('masters/regions/edit-region/{id}', array('as' => 'Region.edit', 'uses' => 'RegionController@update'));
        Route::get('masters/regions/delete-region/{id}', array('as' => 'Region.delete', 'uses' => 'RegionController@delete'));
        Route::get('masters/regions/view-region/{id}', array('as' => 'Region.view', 'uses' => 'RegionController@view'));
        Route::get('masters/regions/update-region-status/{id}/{status}', array('as' => 'Region.status', 'uses' => 'RegionController@changeStatus'));

        /** settings routing**/
        Route::get('masters/partners', array('as' => 'Partner.index', 'uses' => 'PartnerController@index'));
        Route::post('masters/partners', array('as' => 'Partner.index', 'uses' => 'PartnerController@index'));
        Route::get('masters/partners/add-new-partner', array('as' => 'Partner.add', 'uses' => 'PartnerController@add'));
        Route::post('masters/partners/add-new-partner', array('as' => 'Partner.add', 'uses' => 'PartnerController@save'));
        Route::get('masters/partners/edit-partner/{id}', array('as' => 'Partner.edit', 'uses' => 'PartnerController@edit'));
        Route::post('masters/partners/edit-partner/{id}', array('as' => 'Partner.edit', 'uses' => 'PartnerController@update'));
        Route::get('masters/partners/delete-partner/{id}', array('as' => 'Partner.delete', 'uses' => 'PartnerController@delete'));
        Route::get('masters/partners/view-partner/{id}', array('as' => 'Partner.view', 'uses' => 'PartnerController@view'));
        Route::get('masters/partners/update-partner-status/{id}/{status}', array('as' => 'Partner.status', 'uses' => 'PartnerController@changeStatus'));


        /** settings routing**/
        Route::get('masters/domains', array('as' => 'Domain.index', 'uses' => 'DomainController@index'));
        Route::post('masters/domains', array('as' => 'Domain.index', 'uses' => 'DomainController@index'));
        Route::get('masters/domains/add-new-domain', array('as' => 'Domain.add', 'uses' => 'DomainController@add'));
        Route::post('masters/domains/add-new-domain', array('as' => 'Domain.add', 'uses' => 'DomainController@save'));
        Route::get('masters/domains/edit-domain/{id}', array('as' => 'Domain.edit', 'uses' => 'DomainController@edit'));
        Route::post('masters/domains/edit-domain/{id}', array('as' => 'Domain.edit', 'uses' => 'DomainController@update'));
        Route::get('masters/domains/delete-domain/{id}', array('as' => 'Domain.delete', 'uses' => 'DomainController@delete'));
        Route::get('masters/domains/view-domain/{id}', array('as' => 'Domain.view', 'uses' => 'DomainController@view'));
        Route::get('masters/domains/update-domain-status/{id}/{status}', array('as' => 'Domain.status', 'uses' => 'DomainController@changeStatus'));


        /** settings routing**/
        Route::get('masters/lobs', array('as' => 'Lob.index', 'uses' => 'LobController@index'));
        Route::post('masters/lobs', array('as' => 'Lob.index', 'uses' => 'LobController@index'));
        Route::get('masters/lobs/add-new-lob', array('as' => 'Lob.add', 'uses' => 'LobController@add'));
        Route::post('masters/lobs/add-new-lob', array('as' => 'Lob.add', 'uses' => 'LobController@save'));
        Route::get('masters/lobs/edit-lob/{id}', array('as' => 'Lob.edit', 'uses' => 'LobController@edit'));
        Route::post('masters/lobs/edit-lob/{id}', array('as' => 'Lob.edit', 'uses' => 'LobController@update'));
        Route::get('masters/lobs/delete-lob/{id}', array('as' => 'Lob.delete', 'uses' => 'LobController@delete'));
        Route::get('masters/lobs/view-lob/{id}', array('as' => 'Lob.view', 'uses' => 'LobController@view'));
        Route::get('masters/lobs/update-lob-status/{id}/{status}', array('as' => 'Lob.status', 'uses' => 'LobController@changeStatus'));


        /** settings routing**/
        Route::get('masters/designation', array('as' => 'Designation.index', 'uses' => 'DesignationController@index'));
        Route::post('masters/designation', array('as' => 'Designation.index', 'uses' => 'DesignationController@index'));
        Route::get('masters/designation/add-new-designation', array('as' => 'Designation.add', 'uses' => 'DesignationController@add'));
        Route::post('masters/designation/add-new-designation', array('as' => 'Designation.add', 'uses' => 'DesignationController@save'));
        Route::get('masters/designation/edit-designation/{id}', array('as' => 'Designation.edit', 'uses' => 'DesignationController@edit'));
        Route::post('masters/designation/edit-designation/{id}', array('as' => 'Designation.edit', 'uses' => 'DesignationController@update'));
        Route::get('masters/designation/delete-designation/{id}', array('as' => 'Designation.delete', 'uses' => 'DesignationController@delete'));
        Route::get('masters/designation/view-designation/{id}', array('as' => 'Designation.view', 'uses' => 'DesignationController@view'));
        Route::get('masters/designation/update-designation-status/{id}/{status}', array('as' => 'Designation.status', 'uses' => 'DesignationController@changeStatus'));

        /** settings routing**/
        Route::get('training-types', array('as' => 'TrainingType.index', 'uses' => 'TrainingTypeController@index'));
        Route::post('training-types', array('as' => 'TrainingType.index', 'uses' => 'TrainingTypeController@index'));
        Route::get('training-types/add-new-training-type', array('as' => 'TrainingType.add', 'uses' => 'TrainingTypeController@add'));
        Route::post('training-types/add-new-training-type', array('as' => 'TrainingType.add', 'uses' => 'TrainingTypeController@save'));
        Route::get('training-types/edit-training-type/{id}', array('as' => 'TrainingType.edit', 'uses' => 'TrainingTypeController@edit'));
        Route::post('training-types/edit-training-type/{id}', array('as' => 'TrainingType.edit', 'uses' => 'TrainingTypeController@update'));
        Route::get('training-types/delete-training-type/{id}', array('as' => 'TrainingType.delete', 'uses' => 'TrainingTypeController@delete'));
        Route::get('training-types/view-training-type/{id}', array('as' => 'TrainingType.view', 'uses' => 'TrainingTypeController@view'));
        Route::get('training-types/update-training-type-status/{id}/{status}', array('as' => 'TrainingType.status', 'uses' => 'TrainingTypeController@changeStatus'));


        Route::any('admin-roles', array('as' => 'Roles.index', 'uses' => 'AdminRoleController@index'));
        //Route::post('admin-roles',array('as'=>'Roles.index','uses'=>'AdminRoleController@index'));
        Route::get('admin-roles/add-admin-role', array('as' => 'Roles.add', 'uses' => 'AdminRoleController@add'));
        Route::post('admin-roles/add-admin-role', array('as' => 'Roles.add', 'uses' => 'AdminRoleController@save'));
        Route::get('admin-roles/edit-admin-role/{id}', array('as' => 'Roles.edit', 'uses' => 'AdminRoleController@edit'));
        Route::post('admin-roles/edit-admin-role/{id}', array('as' => 'Roles.edit', 'uses' => 'AdminRoleController@update'));
        Route::get('admin-roles/delete-admin-role/{id}', array('as' => 'Roles.delete', 'uses' => 'AdminRoleController@delete'));
        Route::get('admin-roles/view-admin-role/{id}', array('as' => 'Roles.view', 'uses' => 'AdminRoleController@view'));
        Route::get('admin-roles/update-admin-role-status/{id}/{status}', array('as' => 'Roles.status', 'uses' => 'AdminRoleController@changeStatus'));

        Route::get('reports', array('as' => 'Reports.index', 'uses' => 'ReportsController@index'));
        Route::post('reports', array('as' => 'Reports.search', 'uses' => 'ReportsController@search'));

        Route::get('downloads-report-test/{test_id}', array('as' => 'Reports.downloads', 'uses' => 'ReportsController@downloadReport'));
        Route::get('downloads-report-training/{training_id}', array('as' => 'Reports.downloads.training', 'uses' => 'ReportsController@downloadReportTraining'));
    });
});


Route::group(array('prefix' => 'trainer'), function () {
    Route::group(array('middleware' => ['App\Http\Middleware\GuestTrainer', 'PreventBackHistory'], 'namespace' => 'trainer'), function () {
        Route::get('', 'AdminLoginController@login');
        Route::any('/login', 'AdminLoginController@login');
        Route::get('forget_password', 'AdminLoginController@forgetPassword');
        Route::get('reset_password/{validstring}', 'AdminLoginController@resetPassword');
        Route::post('send_password', 'AdminLoginController@sendPassword');
        Route::post('save_password', 'AdminLoginController@resetPasswordSave');
    });

    Route::group(array('middleware' => ['App\Http\Middleware\AuthTrainer', 'PreventBackHistory'], 'namespace' => 'trainer'), function () {
        Route::get('/logout', 'AdminLoginController@logout');
        Route::get('/dashboard', array('as' => 'trainer.dashboard', 'uses' => 'AdminDashboardController@showdashboard'));
        Route::get('/myaccount', 'AdminDashboardController@myaccount');
        Route::post('/myaccount', 'AdminDashboardController@myaccountUpdate');
        Route::get('/bankdetail', 'AdminDashboardController@bankdetail');
        Route::post('/bankdetail', 'AdminDashboardController@bankdetailUpdate');
        Route::get('/change-password', 'AdminDashboardController@change_password');
        Route::post('/changed-password', 'AdminDashboardController@changedPassword');





        /* user routes */
        Route::get('users', array('as' => 'TrainerTrainees.index', 'uses' => 'TraineesController@index'));
        Route::post('users', array('as' => 'TrainerTrainees.index', 'uses' => 'TraineesController@index'));
        Route::get('users/add-new-user', array('as' => 'TrainerTrainees.add', 'uses' => 'TraineesController@add'));
        Route::post('users/add-new-user', array('as' => 'TrainerTrainees.add', 'uses' => 'TraineesController@save'));
        Route::get('users/edit-user/{id}', array('as' => 'TrainerTrainees.edit', 'uses' => 'TraineesController@edit'));
        Route::post('users/edit-user/{id}', array('as' => 'TrainerTrainees.edit', 'uses' => 'TraineesController@update'));
        Route::get('users/delete-user/{id}', array('as' => 'TrainerTrainees.delete', 'uses' => 'TraineesController@delete'));
        Route::get('users/view-user/{id}', array('as' => 'TrainerTrainees.view', 'uses' => 'TraineesController@view'));
        Route::get('users/update-user-status/{id}/{status}', array('as' => 'TrainerTrainees.status', 'uses' => 'TraineesController@changeStatus'));
        // Route::post('trainees/import-trainees', 'TraineesController@importTrainees')->name('import.trainees');
        /* user routes */

        /* training category modules routes */
        Route::get('trainings/category', array('as' => 'TrainerTrainingCategory.index', 'uses' => 'TrainingCategoryController@index'));
        Route::post('trainings/category', array('as' => 'TrainerTrainingCategory.index', 'uses' => 'TrainingCategoryController@index'));
        Route::get('trainings/add-category', array('as' => 'TrainerTrainingCategory.add', 'uses' => 'TrainingCategoryController@add'));
        Route::post('trainings/add-category', array('as' => 'TrainerTrainingCategory.add', 'uses' => 'TrainingCategoryController@save'));
        Route::get('trainings/edit-category/{id}', array('as' => 'TrainerTrainingCategory.edit', 'uses' => 'TrainingCategoryController@edit'));
        Route::post('trainings/edit-category/{id}', array('as' => 'TrainerTrainingCategory.edit', 'uses' => 'TrainingCategoryController@update'));
        Route::get('trainings/delete-category/{id}', array('as' => 'TrainerTrainingCategory.delete', 'uses' => 'TrainingCategoryController@delete'));
        Route::get('trainings/view-category/{id}', array('as' => 'TrainerTrainingCategory.view', 'uses' => 'TrainingCategoryController@view'));
        // Route::get('trainings/update-training-status/{id}/{status}', array('as' => 'TrainerTrainingCategory.status', 'uses' => 'TrainingController@changeStatus'));

        /** Training routing**/
        Route::get('trainings', array('as' => 'TrainerTraining.index', 'uses' => 'TrainingController@index'));
        Route::post('trainings', array('as' => 'TrainerTraining.index', 'uses' => 'TrainingController@index'));
        Route::get('trainings/add-new-training', array('as' => 'TrainerTraining.add', 'uses' => 'TrainingController@add'));
        Route::post('trainings/add-new-training', array('as' => 'TrainerTraining.add', 'uses' => 'TrainingController@save'));
        Route::get('trainings/edit-training/{id}', array('as' => 'TrainerTraining.edit', 'uses' => 'TrainingController@edit'));
        Route::post('trainings/edit-training/{id}', array('as' => 'TrainerTraining.edit', 'uses' => 'TrainingController@update'));
        Route::get('trainings/delete-training/{id}', array('as' => 'TrainerTraining.delete', 'uses' => 'TrainingController@delete'));
        Route::get('trainings/view-training/{id}', array('as' => 'TrainerTraining.view', 'uses' => 'TrainingController@view'));

        Route::get('trainings/update-training-status/{id}/{status}', array('as' => 'TrainerTraining.status', 'uses' => 'TrainingController@changeStatus'));
        Route::get('training/import-training-participants/{id}', 'TrainingController@importTrainingParticipants')->name('import.importTrainerTrainingParticipants');
        Route::post('training/import-training-participants/{id}', 'TrainingController@importTraining')->name('import.training');
        Route::get('training/export-tainings', 'TrainingController@exportTrainerTraining')->name('export.exportTrainerTraining');

        Route::post('trainings/add-more-document', array('as' => 'TrainerTraining.addMoreDocument', 'uses', 'uses' => 'TrainingController@addMoreDocument'));
        Route::post('trainings/delete-more-document', array('as' => 'TrainerTraining.deleteMoreDocument', 'uses', 'uses' => 'TrainingController@deleteMoreDocument'));

        /* course modules routes */
        Route::get('trainings/courses/{training_id}', array('as' => 'TrainingCourse.index', 'uses' => 'CoursesController@index'));
        Route::post('trainings/courses/{training_id}', array('as' => 'TrainingCourse.index', 'uses' => 'CoursesController@index'));
        Route::get('trainings/courses/add-new-course/{training_id}', array('as' => 'TrainingCourse.add', 'uses' => 'CoursesController@add'));
        Route::post('trainings/courses/add-new-course/{training_id}', array('as' => 'TrainingCourse.add', 'uses' => 'CoursesController@save'));
        Route::get('trainings/courses/edit-course/{training_id}/{id}', array('as' => 'TrainingCourse.edit', 'uses' => 'CoursesController@edit'));
        Route::post('trainings/courses/edit-course/{training_id}/{id}', array('as' => 'TrainingCourse.edit', 'uses' => 'CoursesController@update'));
        Route::get('trainings/courses/delete-course/{id}', array('as' => 'TrainingCourse.delete', 'uses' => 'CoursesController@delete'));
        Route::get('trainings/courses/view-course/{id}/{modelId}', array('as' => 'TrainingCourse.view', 'uses' => 'CoursesController@view'));
        Route::get('trainings/courses/update-course-status/{id}/{status}', array('as' => 'TrainingCourse.status', 'uses' => 'CoursesController@changeStatus'));
        Route::post('courses/add-more-document', array('as' => 'TrainingCourse.addMoreDocument', 'uses', 'uses' => 'CoursesController@addMoreDocument'));
        Route::post('courses/delete-more-document', array('as' => 'TrainingCourse.deleteMoreDocument', 'uses', 'uses' => 'CoursesController@deleteMoreDocument'));



        /* test questions routes */
        Route::get('tests/questions/{test_id}', array('as' => 'TrainerQuestion.index', 'uses' => 'QuestionController@index'));
        Route::post('tests/questions/{test_id}', array('as' => 'TrainerQuestion.index', 'uses' => 'QuestionController@index'));
        Route::get('tests/questions/add-new-question/{test_id}', array('as' => 'TrainerQuestion.add', 'uses' => 'QuestionController@add'));
        Route::post('tests/questions/add-new-question/{test_id}', array('as' => 'TrainerQuestion.add', 'uses' => 'QuestionController@save'));
        Route::get('tests/questions/edit-question/{test_id}/{id}', array('as' => 'TrainerQuestion.edit', 'uses' => 'QuestionController@edit'));
        Route::post('tests/questions/edit-question/{test_id}/{id}', array('as' => 'TrainerQuestion.edit', 'uses' => 'QuestionController@update'));
        Route::get('tests/questions/delete-question/{id}', array('as' => 'TrainerQuestion.delete', 'uses' => 'QuestionController@delete'));
        Route::get('tests/questions/view-question/{test_id}/{id}', array('as' => 'TrainerQuestion.view', 'uses' => 'QuestionController@view'));
        Route::get('tests/questions/update-question-status/{id}/{status}', array('as' => 'TrainerQuestion.status', 'uses' => 'QuestionController@changeStatus'));
        Route::post('questions/add-more-option', array('as' => 'Contests.addMoreOption', 'uses', 'uses' => 'QuestionController@addMoreOption'));
        Route::post('questions/delete-more-option', array('as' => 'Contests.deleteMoreOption', 'uses', 'uses' => 'QuestionController@deleteMoreOption'));
        Route::get('/download-sample-file-questions', 'QuestionController@downloadQuestionSample')->name('trainer.download.sample.file.questions');
        Route::post('trainings/import-questions/{test_id}', 'QuestionController@importQuestions')->name('Trainerimport.questions');

        /* test category modules routes */
        Route::get('tests/category', array('as' => 'TrainerTestCategory.index', 'uses' => 'TestCategoryController@index'));
        Route::post('tests/category', array('as' => 'TrainerTestCategory.index', 'uses' => 'TestCategoryController@index'));
        Route::get('tests/add-category', array('as' => 'TrainerTestCategory.add', 'uses' => 'TestCategoryController@add'));
        Route::post('tests/add-category', array('as' => 'TrainerTestCategory.add', 'uses' => 'TestCategoryController@save'));
        Route::get('tests/edit-category/{id}', array('as' => 'TrainerTestCategory.edit', 'uses' => 'TestCategoryController@edit'));
        Route::post('tests/edit-category/{id}', array('as' => 'TrainerTestCategory.edit', 'uses' => 'TestCategoryController@update'));
        Route::get('tests/delete-category/{id}', array('as' => 'TrainerTestCategory.delete', 'uses' => 'TestCategoryController@delete'));
        Route::get('tests/view-category/{id}', array('as' => 'TrainerTestCategory.view', 'uses' => 'TestCategoryController@view'));
        /** test routing**/
        Route::get('tests', array('as' => 'TrainerTest.index', 'uses' => 'TestController@index'));
        Route::post('tests', array('as' => 'TrainerTest.index', 'uses' => 'TestController@index'));
        Route::get('tests/add-new-test', array('as' => 'TrainerTest.add', 'uses' => 'TestController@add'));
        Route::post('tests/add-new-test', array('as' => 'TrainerTest.add', 'uses' => 'TestController@save'));
        Route::get('tests/edit-test/{id}', array('as' => 'TrainerTest.edit', 'uses' => 'TestController@edit'));
        Route::post('tests/edit-test/{id}', array('as' => 'TrainerTest.edit', 'uses' => 'TestController@update'));
        Route::get('tests/delete-test/{id}', array('as' => 'TrainerTest.delete', 'uses' => 'TestController@delete'));
        Route::get('tests/view-test/{id}', array('as' => 'TrainerTest.view', 'uses' => 'TestController@view'));
        Route::get('tests/update-test-status/{id}/{status}', array('as' => 'TrainerTest.status', 'uses' => 'TestController@changeStatus'));
        Route::get('tests/export-tests', 'TestController@exportTrainerTests')->name('export.TrainerTests');
        Route::get('tests/import-tests-participants/{id}', 'TestController@importTrainerTestsParticipants')->name('import.importTrainerTestsParticipants');
        Route::post('tests/import-tests-participants/{id}', 'TestController@importTrainerTests')->name('import.TrainerTests');

        /* test report routes */
        Route::get('test/{test_id}', array('as' => 'TrainerTest.report', 'uses' => 'TestController@testReport'));
    });
});



//trainee panel routes

Route::group(array('middleware' => ['App\Http\Middleware\GuestFront', 'PreventBackHistory'], 'namespace' => 'front'), function () {
    Route::get('', 'AdminLoginController@login');
    Route::any('/login', 'AdminLoginController@login');
    Route::get('forget_password', 'AdminLoginController@forgetPassword');
    Route::get('reset_password/{validstring}', 'AdminLoginController@resetPassword');
    Route::post('send_password', 'AdminLoginController@sendPassword');
    Route::post('save_password', 'AdminLoginController@resetPasswordSave');
});


Route::group(array('middleware' => ['App\Http\Middleware\AuthFront', 'PreventBackHistory'], 'namespace' => 'front'), function () {
    Route::get('/logout', 'AdminLoginController@logout');
    Route::get('/dashboard', array('as' => 'front.dashboard', 'uses' => 'AdminDashboardController@showdashboard'));
    Route::get('/myaccount', 'AdminDashboardController@myaccount');
    Route::post('/myaccount', 'AdminDashboardController@myaccountUpdate');
    Route::get('/bankdetail', 'AdminDashboardController@bankdetail');
    Route::post('/bankdetail', 'AdminDashboardController@bankdetailUpdate');
    Route::get('/change-password', 'AdminDashboardController@change_password');
    Route::post('/changed-password', 'AdminDashboardController@changedPassword');

    Route::get('my-profile', array('as' => 'trainee.profile', 'uses' => 'AdminDashboardController@userProfile'));
    Route::post('my-profile', array('as' => 'trainee.profile.save', 'uses' => 'AdminDashboardController@myaccountUpdate'));
    Route::post('notification-mark-as-read', array('as' => 'markAsRead', 'uses' => 'AdminDashboardController@markNotificationAsRead'));

    /** settings routing**/
    Route::any('my-trainings', array('as' => 'userTraining.index', 'uses' => 'TrainingController@userTrainings'));
    Route::any('my-trainings-details/{id}', array('as' => 'userTrainingDetails.index', 'uses' => 'TrainingController@userTrainingDetails'));
    Route::any('/update-training-document-progress', array('as' => 'userTrainingDetails.document.progress', 'uses' => 'TrainingController@userTrainingDocumentProgress'));
    Route::post('/training-logs/training_details/{id}', array('as' => 'training_details.popup', 'uses' => 'TrainingController@training_details_popup'));

    Route::get('my-trainings/{training_id}/{course_id}/test/{test_id}', array('as' => 'userTraining.test', 'uses' => 'TrainingController@userTrainingTest'));
    Route::post('/submit-training-test-response', array('as' => 'userTraining.test.submit', 'uses' => 'TrainingController@userTrainingTestSubmit'));
    Route::get('/view-training-test-result/{id}', array('as' => 'training.test.result', 'uses' => 'TrainingController@userTrainingTestResult'));
    Route::get('/download-training-certificate-pdf/{id}', array('as' => 'download.user.training.certificate', 'uses' => 'TrainingController@userTrainingCertificateDownload'));

    /** tests routing**/
    Route::any('my-test', array('as' => 'userTest.index', 'uses' => 'TestController@userTests'));
    Route::any('test-details/{id}', array('as' => 'userTestDetails.index', 'uses' => 'TestController@userTestDetails'));
    Route::post('/submit-test-response', array('as' => 'user.test.submit', 'uses' => 'TestController@userTestSubmit'));
    Route::post('/training-test-participant-info', array('as' => 'training.test.participant.info', 'uses' => 'TrainingController@userTrainingTestInfoSubmit'));
    Route::get('/view--test-result/{id}', array('as' => 'user.test.result', 'uses' => 'TestController@userTestResult'));
    Route::get('/download-certificate-pdf/{id}', array('as' => 'download.user.test.certificate', 'uses' => 'TestController@userTestCertificateDownload'));

    Route::post('/update-test-participant-status-attempts', array('as' => 'update.test.participant.status.attempts', 'uses' => 'TestController@userTestParticipantStatus'));

    /** reports routing**/
    Route::any('my-report', array('as' => 'userReport.index', 'uses' => 'ReportController@userReport'));

    /** feedback routing**/
    Route::get('feedback', array('as' => 'userFeedback', 'uses' => 'TrainingController@userFeedback'));
    Route::post('feedback', array('as' => 'store.feedback', 'uses' => 'TrainingController@storeFeedback'));
});



/** Without Authenticate Test Routing**/
Route::get('/test-details/{test_id}/{user_id}', array('as' => 'userTestDetails.index.link', 'uses' => 'testLinkFront\TestController@userTestDetails'));
Route::post('/submit-test-response-link', array('as' => 'user.test.submit.link', 'uses' => 'testLinkFront\TestController@userTestSubmit'));
Route::post('/submit-test-result', array('as' => 'user.test.result.submit.link', 'uses' => 'testLinkFront\TestController@userTestResult'));
Route::get('/view--test-result/{id}/{userId}', array('as' => 'user.test.result.link', 'uses' => 'testLinkFront\TestController@userTestResult'));
Route::post('/update-test-participant-status', array('as' => 'update.test.participant.status.link', 'uses' => 'testLinkFront\TestController@userTestParticipantStatus'));
Route::get('/download-certificate-pdf/{testId}/{attendeeId}', array('as' => 'download.user.test.certificate.link', 'uses' => 'testLinkFront\TestController@userTestCertificateDownload'));

Route::get('/test/{test_id}', array('as' => 'userTestDetails.index.link.copied', 'uses' => 'testLinkFront\TestController@userTestDetailCopied'));
Route::post('/authenticate-user', array('as' => 'authenticate.testLink.attendee', 'uses' => 'testLinkFront\TestController@authenticateTestLinkAttendee'));
Route::get('/test-already-submitted', array('as' => 'userTest.Submitted.ThanksPage', 'uses' => 'testLinkFront\TestController@userTestSubmittedThanksPage'));





// cache clear route via url
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Config cache cleared</h1>';
});
Route::get('/cache-clear', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Application cache cleared</h1>';
});
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>optimize cache cleared</h1>';
});
