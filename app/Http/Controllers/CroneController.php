<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Contests;
use App\Models\StreekGames;
use App\Models\Games;
use App\Models\EmailTemplate;
use App\Models\ContestParticipant;
use App\Models\EmailAction;
use App\Models\Answers;
use App\Models\Payment;
use App\Models\ContestPayout;
use App\Models\User;


use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Input, Mail, Redirect, Request, Response, Session, URL, View, Validator;
use Carbon\Carbon;

/**
 * AvatarController Controller
 *
 * Add your methods in the class below
 *
 */
class CroneController extends BaseController
{
	//streeks

	public function moveStatusToPublish()
	{
		$contestStatus  = 	DB::table('contests')->where('status', 1)->get();

		//	echo '<pre>'; print_r($contestStatus); die;
		foreach ($contestStatus as $contest) {
			$contest_id = $contest->id;
			$publishDateTime = strtotime($contest->publish_date_time);
			$current_date = strtotime(date('Y-m-d H:i:s'));

			if ($current_date > $publishDateTime) {
				$contestsUpdate = DB::table('contests')->where('id', $contest_id)->update(['status' => 2]);
			}
		}
	}
}
