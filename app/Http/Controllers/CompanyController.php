<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        $currentPage = 'about-company';
        $about = Company::first();
        return view('about.about-company', compact('currentPage', 'about'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_motto' => 'nullable|string|max:255',
            'company_phone_no' => 'nullable|string|max:20',
            'company_pan' => 'nullable|string|max:50',
            'company_registration_no' => 'nullable|string|max:100',
            'company_website' => 'nullable|max:255',
            'currency' => 'nullable|string|max:10',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/company'), $logoName);

            $validatedData['company_logo'] = $logoName;
        }

        $company = Company::create($validatedData);

         return redirect()
        ->route('company.about-company')
        ->with('success', 'Product created successfully!');
    }

    public function update(Request $request, $id)
    {   
       
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_address' => 'nullable|string|max:500',
            'company_motto' => 'nullable|string|max:255',
            'company_phone_no' => 'nullable|string|max:20',
            'company_pan' => 'nullable|string|max:50',
            'company_registration_no' => 'nullable|string|max:100',
            'company_website' => 'nullable|max:255',
            'currency' => 'nullable|string|max:10',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $company = Company::findOrFail($id);

        if ($request->hasFile('company_logo')) {
            if ($company->company_logo && file_exists(public_path('uploads/company/' . $company->company_logo))) {
                unlink(public_path('uploads/company/' . $company->company_logo));
            }

            $logo = $request->file('company_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/company'), $logoName);

            $validatedData['company_logo'] = $logoName;
        }

        $company->update($validatedData);

         return redirect()
        ->route('company.about-company')
        ->with('success', 'Company information updated successfully!'); 
    }
}
