<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\RetailAssignedTest;
use App\Models\RetailAssignedTraining;
use App\Models\TraineeAssignedTrainingDocument;
use App\Models\Training;
use App\Models\TrainingDocument;
use App\Models\TrainingParticipants;
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
					'first_name' => $request->name,
					'last_name' => '',
					'fullname' => $request->name,
					'employee_id' => 'Retail-IQ',
					'email' => $request->email,
					'is_from_retail' => '1',
					'password' => Hash::make('Lms@1234'),
					'parent_id' => '1',
					'is_active' => '1',
					'is_mobile_verified' => '1',
					'is_email_verified' => '1',
				]);

				Auth::login($user);
			} else {
				// Optionally: login existing user
				Auth::login($user);
			}

			$data = [];

			// --- 4. Get RetailAssignedTraining trainings
			$queryTraining = RetailAssignedTraining::where('client_id', $request->client_id);

			// Optional: only filter by campaign_id if it's assigned in DB
			if ($request->filled('campaign_id')) {
				$queryTraining->where(function ($q) use ($request) {
					$q->whereRaw("FIND_IN_SET(?, campaign_id)", [$request->campaign_id])
						->orWhereNull('campaign_id')
						->orWhere('campaign_id', '');
				});
			}

			if ($request->filled('store_code')) {
				$queryTraining->where(function ($q) use ($request) {
					$q->whereRaw("FIND_IN_SET(?, store_code)", [$request->store_code])
						->orWhereNull('store_code')
						->orWhere('store_code', '');
				});
			}

			$assignedTrainings = $queryTraining->get();

			foreach ($assignedTrainings as $assigned) {
				$trainingId = $assigned->training_id;

				// Step 1: Get course IDs under this training
				$courseIds = Course::where('training_id', $trainingId)
					->where('is_deleted', 0)
					->pluck('id')
					->toArray();

				if (empty($courseIds)) {
					continue; // no course means no documents → skip or include based on your logic
				}

				// Step 2: Get document IDs from these courses
				$documentIds = TrainingDocument::whereIn('course_id', $courseIds)
					->where('is_active', 1)
					->where('is_deleted', 0)
					->pluck('id')
					->toArray();

				$totalDocs = count($documentIds);

				if ($totalDocs === 0) {
					continue; // no documents to check → treat as complete
				}

				// Step 3: Count user-completed documents
				$completedDocs = TraineeAssignedTrainingDocument::where('user_id', $user->id)
					->where('training_id', $trainingId)
					->whereIn('document_id', $documentIds)
					->where('status', 1)
					->count();

				// Step 4: If incomplete, include training
				if ($completedDocs < $totalDocs) {
					$training = Training::find($trainingId);

					$data[] = [
						'training_name' => $training->title,
						'training_description' => $training->description,
						'training_url' => 'https://lms.qdegrees.com/retail/my-trainings-details/' . $trainingId . '?user_id=' . $user->id,
					];
				}
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
				$query->whereRaw("FIND_IN_SET(?, campaign_id)", [$request->campaign_id]);
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


	public function userTrainingDetails(Request $request)
	{
		try {
			$request->validate([
				'user_id' => 'required',
				'status' => 'required',
			]);

			$trainingIds = TrainingParticipants::where('trainee_id', $request->user_id)->where('status', $request->status)
				->pluck('training_id')
				->unique()
				->toArray();

			$trainings = Training::whereIn('id', $trainingIds)->get();

			return $this->sendSuccess($trainings, config('constants.API_MSG.REC_FETCH_SUCCESS'));
		} catch (ValidationException $e) {
			return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
		} catch (\Exception $e) {
			return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
		}
	}

	public function getTrainingUrl(Request $request)
	{
		try {
			$request->validate([
				'user_id' => 'required',
				'training_id' => 'required',
			]);

			$training_url = 'http://lms.test/retail/my-trainings-details/' . $request->training_id . '?user_id=' . $request->user_id;

			return $this->sendSuccess($training_url, config('constants.API_MSG.REC_FETCH_SUCCESS'));
		} catch (ValidationException $e) {
			return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
		} catch (\Exception $e) {
			return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
		}
	}
}
