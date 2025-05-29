<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Category;
use App\Models\Contact;
class HomePageController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }
    public function contactFormSubmit(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company_name' => 'required',
            'company_url' => 'required',
            'msg' => 'required',
        ]);
        try {
            Contact::create([
                'email' => $request->email,
                'name' => $request->name,
                'number' => $request->phone,
                'company_name' => $request->company_name,
                'company_url' => $request->company_url,
                'msg' => $request->msg,
            ]);
            return back()->with('success', 'Your query submitted successfully .We will contect you soon');
        }catch (\Exception $e) {
            $msg = $e->getMessage();
            return back()->withErrors('error', $msg);
        }
    }
    public function table(Request $request)
    {
        $companies = Company::filterByRequest($request)->paginate(9);
        return view('mainTable.search', compact('companies'));
    }
    public function category(Category $category)
    {
        $companies = Company::join('category_company', 'companies.id', '=', 'category_company.company_id')
            ->where('category_id', $category->id)
            ->paginate(9);
        return view('mainTable.category', compact('companies', 'category'));
    }
    public function company(Company $company)
    {
        return view('mainTable.company', compact ('company'));
    }
    public function privacyPolicy(){
        return view('front-pages.privacy-policy');
    }
    public function aboutUs(){
        return view('front-pages.about-us');
    }
    public function contactUs(){
        return view('front-pages.contact-us');
    }
}
