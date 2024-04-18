<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompaniesRequest;
use App\Http\Requests\UpdateCompaniesRequest;
use App\Models\Companies;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();
        return view('companies.index', compact('user','companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createcompany(Request $request)
    {
        $company = new Companies();
        $company->companyname = $request->input('companyname');
        $company->save();
        return redirect("/companies")->with('status', 'companycreated');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompaniesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Companies $companies)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $company = Companies::select()
            ->where('companyid','=',$id)
            ->first();
        return view('companies.edit', compact('user','company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $company = Companies::select()
            ->where('companyid','=',$id)
            ->first();
        $company->companyname = $request->input('companyname');
        $company->save();
        return redirect("/companies")->with('status', 'companyupdated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Companies::where('companyid', $id)->delete();
        Participants::where('company', $id)->delete();
        return redirect("/companies")->with('status', 'companydeleted');
    }
}
