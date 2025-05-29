<?php

/**
 * Apis Controller
 */

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Contact;
use App\Models\PaymentRequest;
use App\Models\Contests;
use App\Models\Games;
use App\Models\Answers;
use App\Models\ContestGames;
use App\Models\EmailAction;
use App\Models\EmailTemplate;
use App\Models\UserPortfolio;
use App\Models\ContestParticipant;
use App, Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, mongoDate, Redirect, Request, Response, Session, URL, View, Validator, Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ContestsController extends BaseController
{


	public function AllContest()
	{
		$currentUser = Auth::user();
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($currentUser)) {
			if (!empty($formData)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"category_id" 			=> "required",
					),
					array(
						"category_id.required" 	=>	trans("The Category id is required."),
					)
				);

				if ($validator->fails()) {
					$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
					$response["status"]			=	"error";
					$response["message"]		=	$val;
					$response["data"]			=	null;
				} else {


					$user_id	=  $currentUser->id;
					$joind_contests = DB::table('contest_participant')->where('user_id', $user_id)->pluck('contest_id');
					//dd($joind_streeks);
					if (!empty(Request::get('category_id'))) {
						$contests	=	DB::table("contests")
							->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
							->whereNotIn('contests.id', $joind_contests)
							->where('contests.category_id', Request::get('category_id'))
							->where('contests.status', 2)->orderBy('contests.id', 'DESC')
							->select('contests.*', 'categories.category as category_name')
							->get();
					} else {
						$contests	=	DB::table("contests")
							->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
							->whereNotIn('contests.id', $joind_contests)
							->where('contests.status', 2)->orderBy('contests.id', 'DESC')
							->select('contests.*', 'categories.category as category_name')
							->get();
					}



					foreach ($contests as $contest_details) {
						if ($contest_details->image != "" && File::exists(STREEKS_IMAGE_ROOT_PATH . $contest_details->image)) {
							$contest_details->image = STREEKS_IMAGE_URL . $contest_details->image;
						}
						$contest_details->participants = DB::table('contest_participant')->where('contest_id', $contest_details->id)->count();
						$contest_details->contest_payout = DB::table('contest_payout')->where('contest_id', $contest_details->id)->get();
					}

					if ($contests) {
						$response["status"]			=	"success";
						$response["message"]		=	"Contest details found successfully";
						$response["data"]			=	$contests;
					} else {
						$response["status"]			=	"error";
						$response["message"]		=	"Plsease try after some time";
						$response["data"]			=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Invalid Request";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Please login your account first";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to get user details
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function contestDetails()
	{
		$currentUser = Auth::user();
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($currentUser)) {
			if (!empty($formData)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id" 			=> "required"
					),
					array(
						"contest_id.required" 	=>	trans("The contest id is required."),
					),
				);

				if ($validator->fails()) {
					$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
					$response["status"]			=	"error";
					$response["message"]		=	$val;
					$response["data"]			=	null;
				} else {
					$user_id	=  Request::get('user_id');
					$contest_id	=  Request::get('contest_id');

					$contest_details		=	Contests::where('contests.id', $contest_id)
						->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
						->select('contests.*', 'categories.category as category_name')
						->first();


					if (!empty($contest_details)) {

						if ($contest_details->image != "" && File::exists(STREEKS_IMAGE_ROOT_PATH . $contest_details->image)) {
							$contest_details->image = STREEKS_IMAGE_URL . $contest_details->image;
						}
						$contest_details->participants = DB::table('contest_participant')->where('contest_id', $contest_details->id)->count();

						$contest_details->total_streeks_payout  = DB::table('contest_payout')->where('contest_id', $contest_details->id)->sum('amount');
						$contest_details->streeks_payout = DB::table('contest_payout')->where('contest_id', $contest_details->id)->get();

						$stock_id = DB::table('contest_stocks')->where('contest_id', $contest_details->id)->pluck('stock_id');
						$contest_details->contest_stock =  DB::table('stocks')->whereIn('id', $stock_id)->get();


						// $contest_details->created_at = date(Config::get('Reading.date_format'), strtotime($contest_details->created_at));
						// $contest_details->updated_at = date(Config::get('Reading.date_format'), strtotime($contest_details->updated_at));

						// $is_joined  = DB::table('streek_participant')
						// 								->where('contest_id',$contest_id)
						// 								->where('user_id',$user_id)
						// 								->first();
						// if(!empty($is_joined)){
						// 	$contest_details->is_joined  = 1;
						// }else{
						// 	$contest_details->is_joined  = 0;
						// }


						$response["status"]		=	"success";
						$response["message"]	=	"Contest Details Found successfully";
						$response["data"]		=	$contest_details;
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Details not found";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"invalid request";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Please  login your account first";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	/**
	 * Function use for to get user details
	 *
	 * @param null
	 *
	 * @return response
	 */
	public function checkIsjoined()
	{
		$currentUser = Auth::user();
		$formData	=	Request::all();
		$response	=	array();
		if (!empty($currentUser)) {
			if (!empty($formData)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id" 			=> "required"
					),
					array(
						"contest_id.required" 	=>	trans("The contest id is required."),
					),
				);

				if ($validator->fails()) {
					$val = array_column(json_decode(json_encode($validator->errors()), true), 0);
					$response["status"]			=	"error";
					$response["message"]		=	$val;
					$response["data"]			=	null;
				} else {
					$user_id	=  $currentUser->id;
					$contest_id	=  Request::get('contest_id');


					$is_joined  = DB::table('contest_participant')
						->where('contest_id', $contest_id)
						->where('user_id', $user_id)
						->first();

					if (!empty($is_joined)) {
						$response["status"]		=	"success";
						$response["message"]	=	"You have already joined";
						$response["data"]		=	1;
					} else {
						$response["status"]		=	"success";
						$response["message"]	=	"You have not joined now";
						$response["data"]		=	0;
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"invalid request";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Please  login your account first";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}



	public function updateStatus()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"status" 						=> "required",
						"contest_id"							=> "required",
					),
					array(
						"status.required" 					=>	trans("The status is required."),
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$checkDetails				=	Contests::where('id', Request::get("contest_id"))->first();


					if (!empty($checkDetails)) {

						Contests::where('id', Request::get("contest_id"))->update(array('status' => Request::get("status")));

						$response["status"]		=	"success";
						$response["message"]	=	"Contest status has been updated successfully.";
						$response["data"]		=	1;
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid Request";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}



	public function Categories()
	{
		$currentUser = Auth::user();
		if (!empty($currentUser)) {
			$response	=	array();
			$Category	=	DB::table("categories")->where("is_active", 1)->get()->toArray();

			if ($Category) {
				$response["status"]			=	"success";
				$response["message"]		=	"Category details found successfully";
				$response["data"]			=	$Category;
			} else {
				$response["status"]			=	"error";
				$response["message"]		=	"Plsease try after some time";
				$response["data"]			=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}


	public function joinContest()
	{
		$formData	=	Request::all();
		$response	=	array();
		$currentUser = Auth::user();
		if (!empty($currentUser)) {
			if (!empty($formData)) {

				$validator = Validator::make(
					Request::all(),
					array(
						"amount" 						=> "required",
						"contest_id"							=> "required",
					),
					array(
						"amount.required" 					=>	trans("The amount is required."),
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$contest_id			= 	Request::get('contest_id');
					$contest_status  		= 	Contests::where('id', $contest_id)->pluck('status')->first();
					if ($contest_status == 3) {
						$response["status"]			=	"error";
						$response["message"]		=	"Opps! the contest is live now";
						$response["data"]			=	null;
					} else {

						$user_id			= 	$currentUser->id;
						$contest_amount		= 	Request::get('amount');
						$user_amount  		= 	User::where('id', $user_id)->pluck('amount')->first();
						$checkExist 		= 	ContestParticipant::where('user_id', $user_id)->where('contest_id', '=', $contest_id)->first();

						if (!empty($checkExist)) {
							$response["status"]			=	"error";
							$response["message"]		=	"You have already join this streek";
							$response["data"]			=	null;
						} else {
							if ($user_amount > $contest_amount) {
								$RemainingBalance = $user_amount - $contest_amount;
								$updateTotalAmount = User::where('id', $user_id)->update(['amount' => $RemainingBalance]);

								$obj 				=  new ContestParticipant;
								$obj->contest_id	=  Request::get('contest_id');
								$obj->user_id		=  $user_id;
								$obj->amount		=  $contest_amount;
								$obj->status		=  1;

								if ($obj->save()) {
									//send notification

									// $streeks = DB::table('streeks')->where('id',Request::get('contest_id'))->first();
									// $image =$streeks->image;
									// $noti_message  =	'Streek Joind successfully '.$streeks->name.' this streek.';
									// $user_token = DB::table('users')->where('id',$user_id)->select('device_id','device_type','device_token')->first();
									// $data = array('title'=>'Joined Streek','message'=>$noti_message,"image"=>$image,'merchant'=>'yes');

									// if($user_token->device_token != '' && $user_token->device_type != ''){

									//  $this->send_push_notification($user_token->device_token,$user_token->device_type,$data);

									//  DB::table('notifications')->insert(["user_id"=>$user_id,'is_send'=>1,"created_at"=>date("Y-m-d H:i:s"),"updated_at"=>date("Y-m-d H:i:s"),'image'=>$image,'title'=>$data['title'],'message'=>$noti_message]);
									// }


									// $date = date('Y/m/d H:i:s');
									// 	DB::table('transactions')->insert(
									// 		array(
									// 			'contest_id'     =>   $contest_id, 
									// 			'user_id'     =>   $user_id, 
									// 			'amount'   =>   $contest_amount,
									// 			'status'   =>   'done',
									// 			'reason'   =>  'Join Streek',
									// 			'type'   =>  'Deposit',
									// 			'transaction_id'   =>   !empty(Request::input('transaction_id'))?Request::input('transaction_id'):'',
									// 			'created_at'   =>   $date,
									// 				'updated_at'   =>   $date,
									// 		)
									// );



									$response["status"]		=	"success";
									$response["message"]	=	"Contest joined successfully.";
									$response["data"]		=	array();
								} else {
									$response["status"]		=	"error";
									$response["message"]	=	"Something went wrong. Please try again.";
									$response["data"]		=	array();
								}
							} else {
								$response["status"]			=	"error";
								$response["message"]		=	"You Don't have sufficient amount to join this contest.";
								$response["data"]			=	array();
							}
						}
					}
				}
			} else {
				$response["status"]			=	"error";
				$response["message"]		=	"Invalid Request.";
				$response["data"]			=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function upcomingContest()
	{
		$formData	=	Request::all();
		$response	=	array();
		$currentUser = Auth::user();
		if (!empty($currentUser)) {

			$user_id	= $currentUser->id;

			if (isset($formData['name']) && !empty($formData['name'])) {
				$contest = DB::table("contests")->where('contests.status', 2)
					->join('contest_participant', 'contest_participant.contest_id', "=", 'contests.id')
					->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
					->select('contests.*', 'contests.status as contest_status', 'categories.category as category_name')
					->where('contest_participant.status', 1)
					->where("contests.name", 'like', '%' . $formData['name'] . '%')
					->where('contest_participant.user_id', $user_id)
					->orderBy('contests.id', 'DESC')
					->get()->toArray();
			} else {
				$contest = DB::table("contests")->where('contests.status', 2)
					->join('contest_participant', 'contest_participant.contest_id', "=", 'contests.id')
					->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
					->select('contests.*', 'contests.status as contest_status', 'categories.category as category_name')
					->where('contest_participant.status', 1)
					->where('contest_participant.user_id', $user_id)
					->orderBy('contests.id', 'DESC')
					->get()->toArray();
			}
			foreach ($contest as $contest_details) {

				$contest_details->participants = DB::table('contest_participant')->where('contest_id', $contest_details->id)->count();

				if ($contest_details->image != "" && File::exists(STREEKS_IMAGE_ROOT_PATH . $contest_details->image)) {
					$contest_details->image = STREEKS_IMAGE_URL . $contest_details->image;
				}
			}
			if ($contest) {
				$response["status"]			=	"success";
				$response["message"]		=	"Contest details found successfully";
				$response["data"]			=	$contest;
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Details not found";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function myContests()
	{
		$formData	=	Request::all();
		$response	=	array();
		$currentUser = Auth::user();
		if (!empty($currentUser)) {

			$user_id	= $currentUser->id;


			if (isset($formData['name']) && !empty($formData['name'])) {
				$contests = DB::table('contest_participant')
					->join('contests', 'contest_participant.contest_id', "=", 'contests.id')
					->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
					->select('contests.*', 'contests.status as contest_status', 'categories.category as category_name')
					->where('contest_participant.user_id', $user_id)
					->where("contests.name", 'like', '%' . $formData['name'] . '%')
					->where('contests.status', 3)
					->get()->toArray();
			} else {
				$contests = DB::table('contest_participant')
					->join('contests', 'contest_participant.contest_id', "=", 'contests.id')
					->leftJoin('categories', 'categories.id', '=', 'contests.category_id')
					->select('contests.*', 'contests.status as contest_status', 'categories.category as category_name')
					->where('contest_participant.user_id', $user_id)
					->where('contests.status', 3)
					->get()->toArray();
			}

			foreach ($contests as $contest_details) {

				$contest_details->participants = DB::table('contest_participant')->where('contest_id', $contest_details->id)->count();

				if ($contest_details->image != "" && File::exists(STREEKS_IMAGE_ROOT_PATH . $contest_details->image)) {
					$contest_details->image = STREEKS_IMAGE_URL . $contest_details->image;
				}
			}
			if ($contests) {
				$response["status"]			=	"success";
				$response["message"]		=	"Contest details found successfully";
				$response["data"]			=	$contests;
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Details not found";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function leaveContest()
	{
		$formData	=	Request::all();
		$response	=	array();
		$currentUser = Auth::user();
		if (!empty($currentUser)) {
			if (!empty($formData)) {

				$validator = Validator::make(
					Request::all(),
					array(
						"amount" 						=> "required",
						"contest_id"							=> "required",
					),
					array(
						"amount.required" 					=>	trans("The amount is required."),
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$contest_id	= 	Request::get('contest_id');
					$user_id	= $currentUser->id;
					$amount	= 	Request::get('amount');

					$result = ContestParticipant::where('user_id', $user_id)->where('contest_id', $contest_id)->delete();
					$UserAmount = User::where('id', $user_id)->pluck('amount')->first();
					$RemainingBalance = $UserAmount + $amount;
					// $Leavetransactions = DB::table('transactions')->where('contest_id',$contest_id)->where('user_id',$user_id)->delete();
					$updateTotalAmount = User::where('id', $user_id)->update(['amount' => $RemainingBalance]);
					if ($result) {
						$response["status"]			=	"success";
						$response["message"]		=	"Streeks leaved successfully.";
						$response["data"]			=	array();
					} else {
						$response["status"]			=	"error";
						$response["message"]		=	"Details not found.";
						$response["data"]			=	array();
					}
				}
			} else {
				$response["status"]			=	"error";
				$response["message"]		=	"Invalid Request.";
				$response["data"]			=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function contestStocks()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id"							=> "required",
					),
					array(
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$stock_id = DB::table('contest_stocks')->where('contest_id', $formData['contest_id'])->pluck('stock_id');

					if (!empty($stock_id)) {
						if (isset($formData['name']) && !empty($formData['name'])) {
							$contest_stock =  DB::table('stocks')->whereIn('id', $stock_id)->where("stocks.name", 'like', '%' . $formData['name'] . '%')->get();
						} else {
							$contest_stock =  DB::table('stocks')->whereIn('id', $stock_id)->get();
						}

						foreach ($contest_stock as &$stock) {

							$lastDayClose   	=  	$this->lastDayClose($stock->ticker);
							$lastDayClose		=	json_decode($lastDayClose);

							$stock->last_day_close = $lastDayClose;
						}



						if ($contest_stock->isEmpty()) {
							$response["status"]		=	"success";
							$response["message"]	=	"Contest stocks details not found.";
							$response["data"]		=	$contest_stock;
						} else {
							$response["status"]		=	"success";
							$response["message"]	=	"Contest stocks details found successfully.";
							$response["data"]		=	$contest_stock;
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid Request";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function allStocks()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {


				if (isset($formData['name']) && !empty($formData['name'])) {
					$stocks = DB::table('stocks')->where("stocks.name", 'like', '%' . $formData['name'] . '%')->get();
				} else {
					$stocks = DB::table('stocks')->get();
				}

				if ($stocks->isEmpty()) {
					$response["status"]		=	"success";
					$response["message"]	=	"Stocks details not found.";
					$response["data"]		=	$stocks;
				} else {

					$response["status"]		=	"success";
					$response["message"]	=	"Stocks details found successfully.";
					$response["data"]		=	$stocks;
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}


	public function addStockToPortfolio()
	{
		$formData	=	Request::all();
		$response	=	array();
		$currentUser = Auth::user();
		if (!empty($currentUser)) {
			if (!empty($formData)) {

				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id"					=> "required",
						"stock_id"						=> "required",
						"invest_in_type"				=> "required",
						"quantity"						=> "required",
						"amount"							=> "required",
					),
					array(
						"invest_in_type.required" 		=>	trans("The invest in is required."),
						"contest_id.required" 			=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {
					$contest_id			= 	Request::get('contest_id');
					$stock_id			= 	Request::get('stock_id');

					$checkExist 		= 	UserPortfolio::where('user_id', $currentUser->id)->where('contest_id', $contest_id)->where('stock_id', $stock_id)->first();


					//echo '<pre>'; print_r($checkExist); die;
					if (!empty($checkExist)) {
						$old_amount = $checkExist->amount;
						$old_quantity = $checkExist->quantity;


						$update = 	UserPortfolio::where('user_id', $currentUser->id)->where('contest_id', $contest_id)->where('stock_id', $stock_id)->update(array('price' => $old_amount + Request::get('amount'), 'quantity' => $old_quantity + Request::get('quantity')));

						$response["status"]			=	"error";
						$response["message"]		=	"You have already added this stock in your portfolio";
						$response["data"]			=	null;
					} else {
						$obj = new UserPortfolio;
						$obj->contest_id 		= Request::get('contest_id');
						$obj->user_id 			= $currentUser->id;
						$obj->stock_id 			= Request::get('stock_id');
						$obj->invest_in_type 	= Request::get('invest_in_type');
						$obj->quantity 			= Request::get('quantity');
						$obj->amount 			= Request::get('amount');
						$obj->save();

						$response["status"]		=	"success";
						$response["message"]	=	"Stock added successfully to user portfolio.";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]			=	"error";
				$response["message"]		=	"Invalid Request.";
				$response["data"]			=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Login your account first.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function userPortfolioStocks()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id"							=> "required",
					),
					array(
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					$contest_id			= 	Request::get('contest_id');
					$user_id			=   $currentUser->id;


					$stock_ids = DB::table('user_portfolio')->where('contest_id', $contest_id)->where('user_id', $user_id)->pluck('stock_id');

					if (!empty($stock_ids)) {
						if (isset($formData['name']) && !empty($formData['name'])) {
							$Portfolio_stock =  DB::table('stocks')->whereIn('id', $stock_ids)->where("stocks.name", 'like', '%' . $formData['name'] . '%')->get();
						} else {
							$Portfolio_stock =  DB::table('stocks')->whereIn('id', $stock_ids)->get();
						}



						if ($Portfolio_stock->isEmpty()) {
							$response["status"]		=	"error";
							$response["message"]	=	"Portfolio stocks details not found.";
							$response["data"]		=	$Portfolio_stock;
						} else {
							$response["status"]		=	"success";
							$response["message"]	=	"Portfolio stocks details found successfully.";
							$response["data"]		=	$Portfolio_stock;
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid Request";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}

	public function checkContestBudget()
	{

		$formData	=	Request::all();
		$response	=	array();
		if (!empty($formData)) {

			$currentUser = Auth::user();
			if (!empty($currentUser)) {
				$validator = Validator::make(
					Request::all(),
					array(
						"contest_id"							=> "required",
					),
					array(
						"contest_id.required" 					=>	trans("The contest id is required."),
					)
				);
				if ($validator->fails()) {
					$response["status"]			=	"error";
					$response["message"]		=	$validator->errors()->first();
					$response["data"]			=	null;
				} else {

					$contest_id			= 	Request::get('contest_id');
					$user_id			=   $currentUser->id;


					$invest_amount = DB::table('user_portfolio')->where('contest_id', $contest_id)->where('user_id', $user_id)->sum('amount');

					$contest_budget = DB::table('contests')->where('id', $contest_id)->pluck('budget')->first();

					if (!empty($contest_budget)) {

						if ($invest_amount < $contest_budget) {
							$response["status"]		=	"success";
							$response["message"]	=	"You still have budget to buy stocks.";
							$response["data"]		=	array();
						} else {
							$response["status"]		=	"error";
							$response["message"]	=	"Contest budget limit reached.";
							$response["data"]		=	array();
						}
					} else {
						$response["status"]		=	"error";
						$response["message"]	=	"Invalid Request";
						$response["data"]		=	array();
					}
				}
			} else {
				$response["status"]		=	"error";
				$response["message"]	=	"Please login your account first.";
				$response["data"]		=	array();
			}
		} else {
			$response["status"]		=	"error";
			$response["message"]	=	"Invalid Request.";
			$response["data"]		=	array();
		}
		return json_encode($response);
	}
}// end ApisController class
