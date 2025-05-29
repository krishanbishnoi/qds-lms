<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employees;
use Auth;
use DB;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $employeesList = Employees::where('added_by',Auth::user()->id)->get()->toArray();
        return view('corporate.employee.index',compact('employeesList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'employee_id' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'phone_number' => 'required',
            'emp_email' => 'required',
            'manager_email' => 'required',
            'location' => 'required',
            'business_unit' => 'required',
        ]);

        //
        $Employees = Employees::create([
            'added_by' => Auth::user()->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'designation' => $request->designation,
            'phone_number' => $request->phone_number,
            'emp_email' => $request->emp_email,
            'manager_email' => $request->manager_email,
            'location' => $request->location,
            'business_unit' => $request->business_unit,
        ]);

        return response()->json(['status'=>true,'message'=>'Employee Added Successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $Employees = Employees::select('id')->where('id', $id)->first();
        $Employees->delete();

        return back();
    }

    public function importEmployee(){
        $rowEror = [];
        return view('corporate.employee.import',compact('rowEror'));
    }

    public function importCSVFile(Request $request)
    {
        $validated = $request->validate([
            'csvFile' => 'required'
        ]);



        // dd($request->file('csvFile'));
        if (($handle = fopen($request->file('csvFile'), 'r')) !== false) {
            $rowEror = [];
            $i = 0;

            DB::beginTransaction();

            while (($EmployeesRowData = fgetcsv($handle, 0, '|')) !== false) {
                if ($i == 0) {
                    $EmployeesFristRowDataArr = explode(',', $EmployeesRowData[0])??[];
                }
                if ($i > 0) {
                    $dataMissingErr = [];
                    $EmployeesRowDataArr = explode(',', $EmployeesRowData[0]);
                    if($EmployeesRowDataArr && is_array($EmployeesRowDataArr)){
                        foreach($EmployeesRowDataArr as $key => $EmployeesRowData){
                            if(!$EmployeesRowData){
                                $dataMissingErr[] = $EmployeesFristRowDataArr[$key]??[];
                            }

                        }
                    }
                    $addBy = Auth::user()->id;
                    $EmployeeFirstName = $EmployeesRowDataArr[1];
                    $EmployeeLastName = $EmployeesRowDataArr[2];
                    $EmployeeID = $EmployeesRowDataArr[0];
                    $EmployeeDepartment = $EmployeesRowDataArr[3];
                    $EmployeeDesignation = $EmployeesRowDataArr[4];
                    $EmployeePhone = $EmployeesRowDataArr[5];
                    $EmployeeEmail = $EmployeesRowDataArr[6];
                    $EmployeeManagerEmail = $EmployeesRowDataArr[7];
                    $EmployeeLocation = $EmployeesRowDataArr[8];
                    $EmployeeBusinessUnit = $EmployeesRowDataArr[9];

                    $DBQueryResult = DB::insert('insert into employees (added_by, first_name, last_name, employee_id, department, designation, phone_number, emp_email, manager_email, location, business_unit) values ('.$addBy.', "'.$EmployeeFirstName.'","'.$EmployeeLastName.'","'.$EmployeeID.'","'.$EmployeeDepartment.'","'.$EmployeeDesignation.'","'.$EmployeePhone.'","'.$EmployeeEmail.'","'.$EmployeeManagerEmail.'","'.$EmployeeLocation.'","'.$EmployeeBusinessUnit.'")');

                    // dd($DBQueryResult);

                    if($dataMissingErr && is_array($dataMissingErr)){
                        $rowEror[$i]['dataMissingErr'] = $dataMissingErr;
                    }
                }
                $i++;
            }
        }
        if ($rowEror) {
            DB::rollback();
            return view('corporate.employee.import', compact('rowEror'));
        } else {
            DB::commit();
            return redirect()->route('employees.index');
        }
    }

}
