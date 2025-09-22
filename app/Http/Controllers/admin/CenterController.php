<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Model\Center;
use Config;
use Redirect;
use Request;
use Session;
use Validator;
use View;
use Auth;

/**
 * CenterController Controller
 *
 * Add your methods in the class below
 */
class CenterController extends BaseController
{
    public $model = 'Center';

    public $sectionName = 'Center';

    public $sectionNameSingular = 'Center';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);

        // For VAPT Privilege Escalation - Site Wide CATEGORY - Authorization Soluttion
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->user_role_id != 1) {
                return Redirect::to('/')->send();
            }
            return $next($request);
        });
    }

    /**
     * Function for display all State
     *
     * @param null
     * @return view page.
     */
    public function index()
    {
        $DB = Center::query();
        $searchVariable = [];
        $inputGet = Request::all();
        if ((Request::all())) {
            $searchData = Request::all();
            unset($searchData['display']);
            unset($searchData['_token']);
            if (isset($searchData['order'])) {
                unset($searchData['order']);
            }

            if (isset($searchData['sortBy'])) {
                unset($searchData['sortBy']);
            }
            if (isset($searchData['page'])) {
                unset($searchData['page']);
            }

            foreach ($searchData as $fieldName => $fieldValue) {
                if ($fieldValue != '') {
                    if ($fieldName == 'is_active') {
                        $DB->where('cities.is_active', $fieldValue);
                    }
                    if ($fieldName == 'center') {
                        $DB->where('centers.center', 'like', '%' . $fieldValue . '%');
                    }
                }
                $searchVariable = array_merge($searchVariable, [$fieldName => $fieldValue]);
            }
        }
        //$DB->where("areas.is_deleted",0);
        $sortBy = (Request::get('sortBy')) ? Request::get('sortBy') : 'updated_at';
        $order = (Request::get('order')) ? Request::get('order') : 'DESC';
        $results = $DB->orderBy($sortBy, $order)->paginate(Config::get('Reading.records_per_page'));
        $complete_string = Request::query();
        unset($complete_string['sortBy']);
        unset($complete_string['order']);
        $query_string = http_build_query($complete_string);
        $results->appends(Request::all())->render();

        //echo '<pre>'; print_r($results); die;
        return View::make("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    /**
     * Function for add new State
     *
     * @param null
     * @return view page.
     */
    public function add()
    {
        return View::make("admin.$this->model.add");
    } // end add()

    /**
     * Function for save new Area
     *
     * @param null
     * @return redirect page.
     */
    public function save()
    {
        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                'center' => 'required|unique:centers',
                //'description' 		=> 'required',
            ]

        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = new Center;
            $obj->center = Request::get('center');
            $objSave = $obj->save();
            if (!$objSave) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been added successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    } //end save()

    /**
     * Function for update status
     *
     * @param  $modelId  as id of area
     * @param  $status  as status of area
     * @return redirect page.
     */
    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage = trans($this->sectionNameSingular . ' has been deactivated successfully');
        } else {
            $statusMessage = trans($this->sectionNameSingular . ' has been activated successfully');
        }

        Center::where('id', $modelId)->update(['is_active' => $status]);
        Session::flash('flash_notice', $statusMessage);

        return Redirect::back();
    } // end changeStatus()

    /**
     * Function for display page for edit area
     *
     * @param  $modelId  id  of area
     * @return view page.
     */
    public function edit($modelId = 0)
    {
        $model = Center::find($modelId);
        if (empty($model)) {
            return Redirect::route($this->model . '.index');
        }

        return View::make("admin.$this->model.edit", compact('model'));
    } // end edit()

    /**
     * Function for update area
     *
     * @param  $modelId  as id of area
     * @return redirect page.
     */
    public function update($modelId)
    {
        $model = Center::findorFail($modelId);
        if (empty($model)) {
            return Redirect::back();
        }

        Request::replace($this->arrayStripTags(Request::all()));
        $thisData = Request::all();
        //echo '<pre>'; print_r($thisData); die;

        $validator = Validator::make(
            $thisData,
            [
                'center' => "required|unique:centers,center,$modelId",
                //'description' 		=> 'required',
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)->withInput();
        } else {
            $obj = $model;
            $obj->center = Request::get('center');
            $objSave = $obj->save();
            if (!$objSave) {

                Session::flash('error', trans('Something went wrong.'));

                return Redirect::route($this->model . '.index');
            } else {
                Session::flash('success', trans($this->sectionNameSingular . ' has been Updated successfully'));

                return Redirect::route($this->model . '.index');
            }
        }
    } // end update()

    /**
     * Function for mark a couse as deleted
     *
     * @param  $userId  as id of couse
     * @return redirect page.
     */
    public function delete($id = 0)
    {
        $model = Center::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($id) {
            Center::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . ' has been removed successfully'));
        }

        return Redirect::back();
    } // end delete()

}// end CenterController
