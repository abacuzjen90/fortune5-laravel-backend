<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class BranchController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $innerjoin = \DB::table('str_list')
            ->join('emp_masterlist', 'str_list.head_person', '=', 'emp_masterlist.id')
            ->get(['str_list.*', 'emp_masterlist.first_name', 'emp_masterlist.last_name']);
        return $innerjoin;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'str_list_id' => 'required',
            'head_person' => 'required',
            'branchtype_id' => 'required',
            'branchtype' => 'required',
            'acronym' => 'required',
            'description' => 'nullable',
            'str_list_address' => 'required',
            'contact_number' => 'required',
            'per_cbm' => 'nullable|numeric',
            'per_kilo' => 'nullable|numeric',
            'val_charge' => 'nullable|numeric',
            'fcl_value_charge' => 'nullable|numeric',
            'min_charge' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'ftr10' => 'nullable|numeric',
            'ftr20' => 'nullable|numeric',
            'ftr40' => 'nullable|numeric',
            'wheeler4' => 'nullable|numeric',
            'wheeler6' => 'nullable|numeric',
            'wheeler8' => 'nullable|numeric',
            'wheeler10' => 'nullable|numeric',
            'freightliner' => 'nullable|numeric',
            'rolling_cargo' => 'nullable|numeric',
            'ftr10_value' => 'nullable|numeric',
            'ftr20_value' => 'nullable|numeric',
            'ftr40_value' => 'nullable|numeric',
            'wheeler4_value' => 'nullable|numeric',
            'wheeler6_value' => 'nullable|numeric',
            'wheeler8_value' => 'nullable|numeric',
            'wheeler10_value' => 'nullable|numeric',
            'freightliner_value' => 'nullable|numeric',
            'rolling_cargo_value' => 'nullable|numeric',
            'airvalue' => 'nullable|numeric',
            'management_fee' => 'nullable|numeric',
            'agency_10ftr' => 'nullable|numeric',
            'agency_20ftr' => 'nullable|numeric',
            'agency_40ftr' => 'nullable|numeric',
            'small_rate' => 'nullable|numeric',
            'medium_rate' => 'nullable|numeric',
            'large_rate' => 'nullable|numeric',
            'parcel_rate' => 'nullable|numeric',
            'status' => 'nullable',
        ]);

        $branch = Branch::create($field);
        return ['branch' => $branch, 'message' => 'Branch added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $id = $branch->id;
        $branch = \DB::table('str_list')
            ->join('emp_masterlist', 'str_list.head_person', '=', 'emp_masterlist.id')->where('str_list.id', "=", $id)
            ->get(['str_list.*', 'emp_masterlist.first_name', 'emp_masterlist.last_name']);
        return $branch;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $field = $request->validate([
            'str_list_id' => 'required',
            'head_person' => 'required',
            'branchtype_id' => 'required',
            'branchtype' => 'required',
            'acronym' => 'required',
            'description' => 'nullable',
            'str_list_address' => 'required',
            'contact_number' => 'required',
            'per_cbm' => 'numeric|nullable',
            'per_kilo' => 'numeric|nullable',
            'val_charge' => 'numeric|nullable',
            'fcl_value_charge' => 'numeric|nullable',
            'min_charge' => 'numeric|nullable',
            'advalorem' => 'numeric|nullable',
            'ftr10' => 'numeric|nullable',
            'ftr20' => 'numeric|nullable',
            'ftr40' => 'numeric|nullable',
            'wheeler4' => 'numeric|nullable',
            'wheeler6' => 'numeric|nullable',
            'wheeler8' => 'numeric|nullable',
            'wheeler10' => 'numeric|nullable',
            'freightliner' => 'numeric|nullable',
            'rolling_cargo' => 'numeric|nullable',
            'ftr10_value' => 'numeric|nullable',
            'ftr20_value' => 'numeric|nullable',
            'ftr40_value' => 'numeric|nullable',
            'wheeler4_value' => 'numeric|nullable',
            'wheeler6_value' => 'numeric|nullable',
            'wheeler8_value' => 'numeric|nullable',
            'wheeler10_value' => 'numeric|nullable',
            'freightliner_value' => 'numeric|nullable',
            'rolling_cargo_value' => 'numeric|nullable',
            'airvalue' => 'numeric|nullable',
            'management_fee' => 'numeric|nullable',
            'agency_10ftr' => 'numeric|nullable',
            'agency_20ftr' => 'numeric|nullable',
            'agency_40ftr' => 'numeric|nullable',
            'small_rate' => 'numeric|nullable',
            'medium_rate' => 'numeric|nullable',
            'large_rate' => 'numeric|nullable',
            'parcel_rate' => 'numeric|nullable',
            'status' => 'nullable',
        ]);

        $branch->update($field);

        return [$branch];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return ['message' => 'Branch was deleted!'];
    }


    public function branchdata()
    {
        $res = \DB::table('str_branchtype')
            ->join('str_list', 'str_branchtype.id', '=', 'str_list.branchtype_id')
            ->get(['str_branchtype.type', 'str_list.str_list_id']);
        return $res;
    }

    public function branchagency(){
        $branch = \DB::table('str_list')
            ->where('branchtype', "=", 'Agency')
            ->get('str_list_id');
        return $branch;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
