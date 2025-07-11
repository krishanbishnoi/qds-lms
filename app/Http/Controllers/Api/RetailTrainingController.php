<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\RetailAssignedTest;
use App\Models\RetailAssignedTraining;
use App\Models\Training;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class RetailTrainingController extends BaseController
{
	public $res = [];
	public $data = [];

	public function userAudit(Request $request)
	{
		try {
			$request->validate([
				'email' => 'required|email',
				'client_id' => 'required',
				'campaign_id' => 'nullable',
				'store_code' => 'nullable',
			]);

			// 1. Check if user exists
			$user = User::where('email', $request->email)->first();

			// 2. If not, create and log in the user
			if (!$user) {
				$user = User::create([
					'user_role_id' => '3',
					'fullname' => 'Guest Retail User',
					'email' => $request->email,
					'is_from_retail' => '1',
					'password' => Hash::make('Lms@1234'), // temporary password
					'parent_id' => '1', // temporary password
					// Add more fields as per your table structure
				]);

				Auth::login($user);
			} else {
				// Optionally: login existing user
				Auth::login($user);
			}

			$data = [];

			// // 3. Fetch assigned trainings
			// $query = RetailAssignedTest::where('client_id', $request->client_id);

			// if ($request->filled('campaign_id')) {
			// 	$query->where('campaign_id', $request->campaign_id);
			// }

			// if ($request->filled('store_code')) {
			// 	$query->whereRaw("FIND_IN_SET(?, store_code)", [$request->store_code]);
			// }
			// $assignedTests = $query->get();


			// foreach ($assignedTests as $assigned) {
			// 	$data[] = [
			// 		// 'training_url' => 'https://lms.qdegrees.com/my-trainings-details/' . $assigned->training_id . '?user_id=' . $user->id,
			// 		'test_url' => 'https://lms.qdegrees.com/test-details/408' . $assigned->training_id . '?user_id=' . $user->id,
			// 	];
			// }

			// --- 4. Get RetailAssignedTraining trainings
			$queryTraining = RetailAssignedTraining::where('client_id', $request->client_id);

			if ($request->filled('campaign_id')) {
				$queryTraining->where('campaign_id', $request->campaign_id);
			}

			if ($request->filled('store_code')) {
				$queryTraining->whereRaw("FIND_IN_SET(?, store_code)", [$request->store_code]);
			}

			$assignedTrainings = $queryTraining->get();

			foreach ($assignedTrainings as $assigned) {
				$data[] = [
					'training_url' => 'https://lms.qdegrees.com/retail/my-trainings-details/' . $assigned->training_id . '?user_id=' . $user->id,
					// 'test_url' => null  
				];
			}

			return $this->sendSuccess($data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
		} catch (ValidationException $e) {
			return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
		} catch (\Exception $e) {
		return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
		}
	}


	public function getTrainings(Request $request)
	{
		try {
			$request->validate([
				'client_id' => 'required',
				'campaign_id' => 'nullable',
				'store_code' => 'nullable', // comma or single store_code
			]);

			$query = RetailAssignedTraining::where('client_id', $request->client_id);

			if ($request->filled('campaign_id')) {
				$query->where('campaign_id', $request->campaign_id);
			}
			if ($request->filled('store_code')) {
				// Use FIND_IN_SET for comma-separated store_code field
				$query->whereRaw("FIND_IN_SET(?, store_code)", [$request->store_code]);
			}

			$assignedTrainings = $query->get();
			$data = [];

			foreach ($assignedTrainings as $assigned) {
				$training = Training::find($assigned->training_id);

				if (!$training) continue;


				$data[] = [
					'training' => $training,
					'training_courses' => $courses,
				];
			}
			return $this->sendSuccess($data, config('constants.API_MSG.REC_FETCH_SUCCESS'));
		} catch (ValidationException $e) {
			return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
		} catch (\Exception $e) {
			return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
		}
	}
}
