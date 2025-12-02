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
use Illuminate\Support\Facades\DB;


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

			// 1. Check or create user
			$user = User::where('email', $request->email)->first();

			if (!$user) {
				$user = User::create([
					'user_role_id' => '3',
					'first_name' => $request->name,
					'last_name' => '',
					'fullname' => $request->name,
					'employee_id' => 'Retail-IQ',
					'email' => $request->email,
					'designation' => 'Trainee',
					'is_from_retail' => '1',
					'password' => Hash::make('Lms@1234'),
					'parent_id' => '1',
					'is_active' => '1',
					'is_mobile_verified' => '1',
					'is_email_verified' => '1',
				]);
			}

			Auth::login($user);

			$data = [];

			// 2. Get RetailAssignedTraining trainings
			$queryTraining = RetailAssignedTraining::where('client_id', $request->client_id);

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

				$courseIds = Course::where('training_id', $trainingId)
					->where('is_deleted', 0)
					->pluck('id')
					->toArray();

				if (empty($courseIds)) continue;

				$documentIds = TrainingDocument::whereIn('course_id', $courseIds)
					->where('is_active', 1)
					->where('is_deleted', 0)
					->pluck('id')
					->toArray();

				$totalDocs = count($documentIds);
				if ($totalDocs === 0) continue;

				$completedDocs = TraineeAssignedTrainingDocument::where('user_id', $user->id)
					->where('training_id', $trainingId)
					->whereIn('document_id', $documentIds)
					->where('status', 1)
					->count();

				if ($completedDocs < $totalDocs) {
					// === Auto assign participant & documents ===
					$alreadyAssigned = TrainingParticipants::where('training_id', $trainingId)
						->where('trainee_id', $user->id)
						->exists();
					if (! $alreadyAssigned) {
						// Add participant
						TrainingParticipants::create([
							'training_id' => $trainingId,
							'trainee_id' => $user->id,
						]);

						// Assign documents
						$documentsToInsert = [];

						$documents = TrainingDocument::whereIn('course_id', $courseIds)
							->where('is_active', 1)
							->where('is_deleted', 0)
							->get();
						foreach ($documents as $doc) {
							$documentsToInsert[] = [
								'user_id' => $user->id,
								'training_id' => $trainingId,
								'course_id' => $doc->course_id,
								'document_id' => $doc->id,
								'type' => $doc->type,
								'duration' => $doc->length,
								'status' => 0,
								'created_at' => now(),
								'updated_at' => now(),
							];
						}

						if (!empty($documentsToInsert)) {
							DB::table('trainee_assigned_training_documents')->insert($documentsToInsert);
						}
					}

					$training = Training::find($trainingId);

					$data[] = [
						'training_name' => $training->title,
						'training_description' => $training->description,
						'training_url' => 'https://csilms.qdegrees.com/retail/my-trainings-details/' . $trainingId . '?user_id=' . $user->id,
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




	public function userTrainingDetails(Request $request)
	{
		try {
			$request->validate([
				'email' => 'required',
				'status' => 'required',
			]);

			$userId = User::where('email', $request->email)->value('id');
			if (!$userId) {
				return $this->sendError('User Not found', [], 422);
			}

			$trainingIds = TrainingParticipants::where('trainee_id', $userId)
				->where('status', $request->status)
				->pluck('training_id')
				->unique()
				->toArray();

			$trainings = Training::whereIn('id', $trainingIds)->get();

			$result = [];

			foreach ($trainings as $training) {
				// Get all course IDs for the training
				$courseIds = Course::where('training_id', $training->id)->pluck('id')->toArray();

				// Get all document IDs for the training (via courses)
				$documentIds = TrainingDocument::whereIn('course_id', $courseIds)->pluck('id')->toArray();

				$totalDocuments = count($documentIds);

				// Get documents completed by user (status = 1)
				$completedDocumentCount = TraineeAssignedTrainingDocument::where('user_id', $userId)
					->where('training_id', $training->id)
					->where('status', 1)
					->whereIn('document_id', $documentIds)
					->count();

				// Avoid division by zero
				$completionPercentage = $totalDocuments > 0
					? round(($completedDocumentCount / $totalDocuments) * 100, 2)
					: 0;
				$completionPercentage = number_format($completionPercentage,2);
				// Add percentage to training
				$training->completion_percentage = $completionPercentage;

				// Append full thumbnail URL 
				$training->thumbnail = $training->thumbnail
					? asset('training_document/' . $training->thumbnail)
					: null;
 

				$result[] = $training;
			}

			return $this->sendSuccess($result, config('constants.API_MSG.REC_FETCH_SUCCESS'));
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
				'email' => 'required',
				'training_id' => 'required',
			]);
			$userId = User::where('email', $request->email)->value('id');
			if (!$userId) {
				return $this->sendError('User Not found', [], 422);
			}


			$training_url = 'https://csilms.qdegrees.com/retail/my-trainings-details/' . $request->training_id . '?user_id=' . $userId;

			return $this->sendSuccess($training_url, config('constants.API_MSG.REC_FETCH_SUCCESS'));
		} catch (ValidationException $e) {
			return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
		} catch (\Exception $e) {
			return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
		}
	}
}
