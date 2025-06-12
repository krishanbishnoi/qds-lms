<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Models\Course;
use App\Models\RetailAssignedTraining;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class RetailTrainingController extends BaseController
{
	public $res = [];
	public $data = [];
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
