<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\BankDetail;
use App\Models\Training;
use App\Models\TrainingDocument;
use App\Models\TrainingType;
use App\Models\TrainingParticipants;
use App\Models\TestParticipants;
use App\Models\StateDescription;
use App\Models\Test;
use Illuminate\Http\Request;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Response, Session, URL, View, Validator;

/**
 * AdminDashBoard Controller
 *
 * Add your methods in the class below
 *
 * This file will render views\front\dashboard
 */
class AdminDashboardController extends BaseController
{
    /**
     * Function for display front dashboard
     *
     * @param null
     *

     * @return view page.
     */
    public $model        =    'Training';
    public $sectionName    =    'Training';
    public $sectionNameSingular    =    'Training';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }




    /**
     * Function for display page for edit area
     *
     * @param $modelId id  of area
     *
     * @return view page.
     */

    public function showdashboard()
    {

        $myTrainingsIds = TrainingParticipants::where('trainee_id', Auth::user()->id)->pluck('training_id')->toArray();
        //   echo '<pre>'; print_r($myTrainingsIds); die;
        if (!empty($myTrainingsIds)) {
            $ongoing = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 1)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $upcoming = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 0)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
            $completed = Training::whereIn('trainings.id', $myTrainingsIds)->where('trainings.status', 2)->leftJoin('training_types', 'training_types.id', '=', 'trainings.type')->leftJoin('users', 'users.id', '=', 'trainings.user_id')->select('trainings.*', 'training_types.type as type', 'users.first_name as created_by')->orderBy('trainings.id', 'DESC')->get();
        } else {
            $ongoing = '';
            $upcoming = '';
            $completed = '';
        }
        $myTestIds = TestParticipants::where('trainee_id', Auth::user()->id)->pluck('test_id')->toArray();
        //   echo '<pre>'; print_r($myTestIds); die;
        if (!empty($myTestIds)) {
            $ongoingTests = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 0)->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $upcomingTests = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 1)->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
            $completedTests = Test::whereIn('tests.id', $myTestIds)->where('tests.status', 1)->leftJoin('users', 'users.id', '=', 'tests.user_id')->select('tests.*', 'users.first_name as created_by')->orderBy('tests.id', 'DESC')->get();
        } else {
            $ongoingTests = '';
            $upcomingTests = '';
            $completedTests = '';
        }

        // For get notifications
        $user = User::find(Auth::user()->id);
        $notifications = $user->notifications->where('read_at', '');

        return  View::make('front.dashboard.dashboard', compact('ongoing', 'upcoming', 'completed', 'ongoingTests', 'upcomingTests', 'completedTests', 'notifications'));
        // return  View::make("front.$this->model.userTraining", compact('myTrainings'));
    }
    public function markNotificationAsRead(Request $request)
    {
        $notificationId = $request->notification_id;
        $user = User::find(Auth::user()->id);
        $notification = $user->notifications->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }
        return response()->json(['error' => 'Notification not found'], 404);
    }

    /**
     * Function for display front account detail
     *
     * @param null
     *
     * @return view page.
     */
    public function myaccount()
    {
        return  View::make('front.dashboard.myaccount');
    } // end myaccount()
    /**
     * Function for display bank detail
     *
     * @param null
     *
     * @return view page.
     */
    public function bankdetail()
    {
        $userInfo = BankDetail::where('user_id', Auth::user()->id)->first();
        return  View::make('front.dashboard.bankdetail', compact('userInfo'));
    } // end myaccount()
    /**


     * Function for change_password
     *
     * @param null
     *
     * @return view page.
     */
    public function change_password()
    {
        return  View::make('front.dashboard.change_password');
    } // end myaccount()
    /**
     * Function for update front account update
     *
     * @param null
     *
     * @return redirect page.
     */
    // Make sure to import the Request class at the top of your controller

    public function myaccountUpdate(Request $request)
    {
        $thisData = $request->all();
        $request->replace($this->arrayStripTags($thisData));


        $user = User::find(auth()->user()->id);

        if ($request->hasFile('profile_image')) {
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName = time() . '-user.' . $extension;

            $folderName = strtoupper(date('M') . date('Y')) . "/";
            $folderPath = USER_IMAGE_ROOT_PATH . $folderName;

            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, $mode = 0777, true);
            }

            if ($request->file('profile_image')->move($folderPath, $fileName)) {
                $user->image = $folderName . $fileName;
            }
        }

        if ($user->save()) {
            return redirect()->back()
                ->with('success', 'Information updated successfully.');
        }
    }

    /**
     *
     * Function for update front bank detail update
     *
     * @param null
     *
     * @return redirect page.
     */
    public function bankdetailUpdate()
    {
        $thisData                =    Request::all();
        //dd($thisData);
        //print_r($thisData); die;
        Request::replace($this->arrayStripTags($thisData));
        $ValidationRule = array(
            'account_holder_name' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
            'bank_name' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
            'account_number' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/',
            'account_type' => 'required|Regex:/\A(?!.*[:;]-\))[ -~]+\z/',
            'iban_number' => 'required|numeric|regex:/^\s*(?=.*[1-9])\d*(?:\.\d{1,2})?\s*$/',
        );
        $validator                 =     Validator::make(Request::all(), $ValidationRule);
        if ($validator->fails()) {
            return Redirect::to('/bankdetail')
                ->withErrors($validator)->withInput();
        } else {
            $user                         =     BankDetail::where('user_id', Auth::user()->id)->first();
            $user->account_holder_name    =     Request::get('account_holder_name');
            $user->bank_name            =   Request::get('bank_name');
            $user->account_number        =   Request::get('account_number');
            $user->account_type             =     Request::get('account_type');
            $user->iban_number            =   Request::get('iban_number');

            if ($user->save()) {
                return Redirect::intended('/bankdetail')
                    ->with('success', 'Information updated successfully.');
            }
        }
    } // end bankdetails()
    /**
     * Function for changedPassword
     *
     * @param null
     *
     * @return redirect page.
     */
    public function changedPassword()
    {
        $thisData                =    Request::all();
        Request::replace($this->arrayStripTags($thisData));
        $old_password            =     Request::get('old_password');
        $password                 =     Request::get('new_password');
        $confirm_password         =     Request::get('confirm_password');
        Validator::extend('custom_password', function ($attribute, $value, $parameters) {
            if (preg_match('#[0-9]#', $value) && preg_match('#[a-zA-Z]#', $value) && preg_match('#[\W]#', $value)) {
                return true;
            } else {
                return false;
            }
        });
        $rules                      =     array(
            'old_password'         =>    'required|max:255',
            'new_password'        =>    'required|min:8|max:255|custom_password',
            'confirm_password'  =>    'required|max:255|same:new_password'
        );
        $validator                 =     Validator::make(
            Request::all(),
            $rules,
            array(
                "new_password.custom_password"    =>    "Password must have combination of numeric, alphabet and special characters.",
            )
        );
        if ($validator->fails()) {
            return Redirect::to('/change-password')
                ->withErrors($validator)->withInput();
        } else {
            $user                 = User::find(Auth::user()->id);
            $old_password         = Request::get('old_password');
            $password             = Request::get('new_password');
            $confirm_password     = Request::get('confirm_password');
            if ($old_password != '') {
                if (!Hash::check($old_password, $user->password)) {
                    /* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.');
						 */
                    Session::flash('error', trans("Your old password is incorrect."));
                    return Redirect::to('/change-password');
                }
            }
            if (!empty($old_password) && !empty($password) && !empty($confirm_password)) {
                if (Hash::check($old_password, $user->password)) {
                    $user->password = Hash::make($password);
                    // save the new password
                    if ($user->save()) {
                        Session::flash('success', trans("Password changed successfully."));
                        return Redirect::to('/change-password');
                    }
                } else {
                    /* return Redirect::intended('change-password')
						->with('error', 'Your old password is incorrect.'); */
                    Session::flash('error', trans("Your old password is incorrect."));
                    return Redirect::to('/change-password');
                }
            } else {
                $user->username = $username;
                if ($user->save()) {
                    Session::flash('success', trans("Password changed successfully."));
                    return Redirect::to('/change-password');
                    /* return Redirect::intended('change-password')
						->with('success', 'Password changed successfully.'); */
                }
            }
        }
    } // end myaccountUpdate()
    /*
* For User Listing Demo
*/
    public function usersListing()
    {
        return View::make('front.user.user');
    }
    public function userProfile()
    {
        $userProfile = User::where('id', Auth::user()->id)->with('parentManager')->first();
        // For get notifications
        $notifications = $userProfile->notifications->where('read_at', '');
        $userProfile->unreadNotifications->markAsRead();
        return  View::make('front.dashboard.my-profile', compact('userProfile', 'notifications'));
    }
    public function userProfileSave(Request $request)
    {
        $request->validate([
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userId = $request->input('id');

        $image = $request->file('profile_image');
        $imageName = $userId . '.' . $image->getClientOriginalExtension();
        $image->storeAs('profile_images', $imageName);

        $user = User::find($userId);
        $user->image = $imageName;
        $user->save();

        // Redirect back or to a different page as needed
        return redirect()->back()->with('success', 'Profile image updated successfully');
    }


    // public function userProfileSave(Request $request)
    // {
    //     $data = User::find($request->id);

    //     if ($request->hasFile('profile')) {
    //         $file = $request->file('profile');
    //         $extension = $file->getClientOriginalExtension();
    //         $filename = time() . '.' . $extension;
    //         $file->move('user_images/trainee-profile', $filename);
    //         $data->image = $filename;
    //     }
    //     $data->update();
    //     return Redirect::back();

    // }
    // public function userProfileSave(Request $request)
    // {
    // 	$userProfile = User::where('id', Auth::user()->id)->first();

    //     if ($request->hasFile('profile')) {
    //         $file = $request->file('profile');
    //         $extension = $file->getClientOriginalExtension();
    //         $fileName    =    time() . '-profile.' . $extension;

    //         $folderName         =     strtoupper(date('M') . date('Y')) . "/";

    //         $folderPath            =    USER_IMAGE_ROOT_PATH . $folderName;

    //         if (!File::exists($folderPath)) {
    //             File::makeDirectory($folderPath, $mode = 0777, true);
    //         }
    //         if ($request->hasFile('profile')->move($folderPath, $fileName)) {
    //             $userProfile->image    =    $folderName . $fileName;
    //         }
    //     }
    //     $userProfile->save();

    // }
} //end AdminDashBoardController()
