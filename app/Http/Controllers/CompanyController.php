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
        ]);
    
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
        ]);
    
        $company = Company::findOrFail($id);
        $company->update($validatedData);

         return redirect()
        ->route('company.about-company')
        ->with('success', 'Company information updated successfully!'); 
    }
}
