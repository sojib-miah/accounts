<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->when(!Auth::user()->hasRole('Super-Admin'), function ($query) {
            $query->where('created_by', Auth::id());
        })->latest()->get();
        return view('BackEnd.Company.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:companies,name',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hologram' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seal' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $company = new Company();
        $company->name = $request->name;
        $company->created_by = Auth::id();
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = time() . '_logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $name);
            $company->logo = 'uploads/company/' . $name;
        }
        if ($request->hasFile('hologram')) {
            $file = $request->file('hologram');
            $name = time() . '_hologram.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $name);
            $company->hologram = 'uploads/company/' . $name;
        }
        if ($request->hasFile('seal')) {
            $file = $request->file('seal');
            $name = time() . '_seal.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $name);
            $company->seal = 'uploads/company/' . $name;
        }
        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $name = time() . '_signature.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/company'), $name);
            $company->signature = 'uploads/company/' . $name;
        }
        $company->save();
        return redirect()->route('company.index')->with('success', 'Company Created Successfully');
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|max:255|unique:companies,name,' . $company->id,
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hologram' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'seal' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'signature' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $company->name = $request->name;
        foreach (['logo', 'hologram', 'seal', 'signature'] as $field) {
            if ($request->hasFile($field)) {
                if ($company->$field && File::exists(public_path($company->$field))) {
                    File::delete(public_path($company->$field));
                }
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/company'), $filename);
                $company->$field = 'uploads/company/' . $filename;
            }
        }
        $company->save();
        return redirect()->route('company.index')->with('success', 'Company Updated Successfully');
    }

    public function destroy(Company $company)
    {
        if ($company->branches()->exists()) {

            return back()->with(
                'error',
                'Company cannot be deleted because it has branches.'
            );
        }

        foreach (['logo', 'hologram', 'seal', 'signature'] as $field) {
            if ($company->$field && File::exists(public_path($company->$field))) {
                File::delete(public_path($company->$field));
            }
        }
        $company->delete();
        return redirect()->route('company.index')->with('success', 'Company Deleted Successfully');
    }
}
