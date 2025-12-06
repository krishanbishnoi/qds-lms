<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\BaseController;
use App\Models\Agency;
use App\Models\City;
use App\Models\Lob;
use App\Models\Region;
use App\Models\State;
use App\Models\StateDescription;
use Auth, Blade, Config, Cache, Cookie, DB, File, Hash, Mail, Redirect, Response, Session, URL, View, Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

class AgencyController extends BaseController
{
    public $data = [];

    public $model        =    'Agency';
    public $sectionName    =    'Agency';
    public $sectionNameSingular    =    'Agency';

    public function __construct()
    {
        parent::__construct();
        View::share('modelName', $this->model);
        View::share('sectionName', $this->sectionName);
        View::share('sectionNameSingular', $this->sectionNameSingular);
    }

    public function index(Request $request)
    {
        $search = $request->all();

        $query = Agency::with(['region', 'state', 'city']);

        if (!empty($request->name)) {
            $query->where('name', 'LIKE', "%{$request->name}%");
        }

        $sortBy = $request->get('sortBy', 'id');
        $order  = $request->get('order', 'desc');

        $results = $query->orderBy($sortBy, $order)->paginate(10);
        $results->appends($request->all());

        $this->data['results'] = $results;
        $this->data['searchVariable'] = $search;
        $this->data['sortBy'] = $sortBy;
        $this->data['order'] = $order;
        $this->data['query_string'] = http_build_query($search);

        return view('admin.Agency.index', $this->data);
    }

    public function add()
    {
        $this->data['states'] = State::pluck('name', 'id');
        $this->data['cities'] = [];
        $this->data['regions'] = Region::pluck('region', 'id');
        return view("admin.Agency.add", $this->data);
    }

    public function save(Request $request)
    {
        try {
            $request->replace($this->arrayStripTags($request->all()));
            $input = $request->all();
            $rules = [
                'name'        => 'required',
                'email'       => 'required|email',
                'mobile_no'   => 'required',
                'agency_code' => "required|unique:agencies,agency_code,{$request->id}",
                'region_id'   => 'required',
                'state_id'    => 'required',
                'city_id'     => 'required',
                'agency_manager_name'     => 'required',
                'agency_manager_email'     => 'required',
            ];

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $data = [
                'name'                     => $request->name,
                'email'                    => $request->email,
                'mobile_no'                => $request->mobile_no,
                'agency_code'              => $request->agency_code,
                'region_id'                => $request->region_id,
                'state_id'                 => $request->state_id,
                'city_id'                  => $request->city_id,
                'address'                  => $request->address,
                'agency_manager_name'      => $request->agency_manager_name,
                'agency_manager_email'     => $request->agency_manager_email,
                'is_active'                => $request->is_active ?? 1,
            ];

            $agency = Agency::updateOrCreate(
                ['id' => $request->id],
                $data
            );

            if (!$agency) {
                FacadesSession::flash('error', __(config('constants.REC_ADD_FAILED')));
            } else {
                $message = $request->id
                    ? __(config('constants.REC_UPDATE_SUCCESS'), ['section' => $this->sectionNameSingular])
                    : __(config('constants.REC_ADD_SUCCESS'), ['section' => $this->sectionNameSingular]);

                FacadesSession::flash('success', $message);
            }

            return redirect()->route('Agency.index');
        } catch (\Exception $e) {
            return redirect()->route('Agency.index')->with(['error' => 'Something went wrong']);
        }
    }



    public function changeStatus($modelId = 0, $status = 0)
    {
        if ($status == 0) {
            $statusMessage    =    trans($this->sectionNameSingular . " has been deactivated successfully");
        } else {
            $statusMessage    =    trans($this->sectionNameSingular . " has been activated successfully");
        }

        Agency::where('id', $modelId)->update(array('status' => $status));
        Session::flash('flash_notice', $statusMessage);
        return Redirect::back();
    }


    public function edit($id)
    {
        $model = Agency::with(['region', 'state', 'city'])->findOrFail($id);
        $regions = Region::pluck('region', 'id');
        $states = State::pluck('name', 'id');
        $cities = City::where('state_id', $model->state_id)
            ->pluck('city', 'id');

        return view('admin.Agency.add', [
            'model' => $model,
            'regions' => $regions,
            'states' => $states,
            'cities' => $cities,
        ]);
    }

    public function delete($id = 0)
    {
        $model    =    Agency::find($id);
        if (empty($model)) {
            return Redirect::back();
        }
        if ($model) {
            Agency::where('id', $id)->delete();
            Session::flash('flash_notice', trans($this->sectionNameSingular . " has been removed successfully"));
        }
        return Redirect::back();
    }
    public function getCities($state)
    {
        $cities = City::where('state_id', $state)->pluck('city', 'id');
        return response()->json($cities);
    }
}
