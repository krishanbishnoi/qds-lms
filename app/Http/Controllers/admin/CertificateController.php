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
        // Step 1: Reset all certificates
        Certificate::query()->update(['is_active' => 0]);

        // Step 2: Activate selected certificate
        $certificate = Certificate::find($id);

        if ($certificate) {
            $certificate->is_active = 1;
            $certificate->save();
        }

        return redirect()->back()->with('success', 'Certificate Selected Successfully.');
    }
}
