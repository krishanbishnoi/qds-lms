<?php

namespace App\Http\Controllers;

use DB;

/**
 * AvatarController Controller
 *
 * Add your methods in the class below
 */
class CroneController extends BaseController
{
    //streeks

    public function moveStatusToPublish()
    {
        $contestStatus = DB::table('contests')->where('status', 1)->get();

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
