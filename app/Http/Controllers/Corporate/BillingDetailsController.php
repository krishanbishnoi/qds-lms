<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BillingDetails;
use Auth;

class BillingDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $BillingDetailsList = BillingDetails::get();
        return view('corporate.billings.billing-details',compact('BillingDetailsList'));
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
        //
        $request->validate([
            'entity_name' => 'required',
            'gst_number' => 'required',
            'address_1' => 'required',
            'pincode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        //
        $BillingDetailsList = BillingDetails::get()->toArray();
        $BillingDetails = BillingDetails::create([
            'user_id' => Auth::user()->id,
            'entity_name' => $request->entity_name,
            'gst_number' => $request->gst_number,
            'is_default' => (count($BillingDetailsList) > 0)?0:1,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'pincode' => $request->pincode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
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
        $request->validate([
            'entity_name' => 'required',
            'gst_number' => 'required',
            'address_1' => 'required',
            'city' => 'required',
            'pincode' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        
        $BillingDetailsList = BillingDetails::get()->toArray();
        $is_default = $request->is_default??0;
        if($request->is_default == 'on'){
            $is_default = 1;
            BillingDetails::where('user_id',Auth::user()->id)->update(['is_default' => 0]);
        }

        $BillingDetails = BillingDetails::updateOrCreate(['id' => $request->id], [
            'user_id' => Auth::user()->id,
            'entity_name' => $request->entity_name,
            'gst_number' => $request->gst_number,
            'is_default' => count($BillingDetailsList) > 1?$is_default:1,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'pincode' => $request->pincode,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ]);

        return response()->json(['status'=>true,'message'=>'Billing details updated.']);
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
        $BillingDetails = BillingDetails::select(['id','is_default'])->where('id', $id)->first();
        if($BillingDetails->is_default){
            return response()->json(['status'=>false,'message'=>'You can\'t delete default entity.']);
        }else{
            $BillingDetails->delete();
            return response()->json(['status'=>true,'message'=>'Billing details deleted.']);
        }
        // return back();
    }
}
