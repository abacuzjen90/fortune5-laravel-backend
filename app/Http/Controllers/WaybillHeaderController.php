<?php

namespace App\Http\Controllers;

use App\Models\WaybillHeader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class WaybillHeaderController extends Controller implements HasMiddleware
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
        return WaybillHeader::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
           'type' => 'required',
           'destination_to' => 'required',
            'waybillno' => 'required',
            'hwaybillnumber' => 'required',
            'crs_number' => 'required',
            'modeoftransaction' => 'required',
            'charge_to' => 'required',
            'shipper' => 'required',
            'consignee' => 'required',
            'waybill_address' => 'nullable',
            'address' => 'nullable',
            'mobile_number' => 'nullable',
            'contact_number' => 'nullable',
            'destination_from' => 'nullable',
            'memo' => 'nullable',
            'shipper_own_risk' => 'nullable',
            'wb_missing_status' => 'nullable',
            'waybilldate' => 'nullable',
            'ptf_status' => 'nullable',
            'terms' => 'nullable',
            'customer_minimum' => 'nullable',
            'encoder' => 'nullable',
            'encoded' => 'nullable',
            'time' => 'nullable',
            'appraiser' => 'nullable',
            'pickupby' => 'nullable',
            'typist_name' => 'nullable',
            'customer_dr_attachment' => 'nullable',
            'rates_to_apply' => 'nullable',
            'glass' => 'nullable',
            'liquid' => 'nullable',
            'breakable' => 'nullable',
            'food' => 'nullable',
            'perishable' => 'nullable',
        ]);

        $field['food'] .= $field['perishable'];
        $field['encoder'] = $request->user()->id;
        $field['encoded'] = date('Y-m-d');
        $field['time'] = date('g:i A');

        $waybillheader = WaybillHeader::create($field);
        return ['waybillheader' => $waybillheader, 'message' => 'Waybill added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(WaybillHeader $waybillHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaybillHeader $waybillHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaybillHeader $waybillHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaybillHeader $waybillHeader)
    {
        //
    }

    public function getWaybillNo($id)
    {
        $count = WaybillHeader::where('encoder', $id)->count();
        $waybillNo = str_pad($count + 1, 8, '0', STR_PAD_LEFT);

        return response()->json([
            'waybill_no' => $waybillNo
        ]);
    }
}
