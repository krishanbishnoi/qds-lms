<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $certificates = Certificate::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.certificates.index', compact('certificates'));
    }
    public function changeStatus($id)
    {
        $existingCertificates = Certificate::get();
        foreach($existingCertificates as $certificate){
            $certificate['is_active'] = 0;
        }
        $certificate = Certificate::where('id',$id)->first();
        if($certificate->is_active == 0){
            $certificate->is_active = 1;
        }
        return redirect()->back()->with(['success','Certificate Selected Successfully.']);
    }

}
