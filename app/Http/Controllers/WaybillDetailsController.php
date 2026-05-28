<?php

namespace App\Http\Controllers;

use App\Models\WaybillDetails;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class WaybillDetailsController extends Controller implements HasMiddleware
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
        return WaybillDetails::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WaybillDetails $waybillDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WaybillDetails $waybillDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WaybillDetails $waybillDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WaybillDetails $waybillDetails)
    {
        //
    }


    public function saveAdvalorem(Request $request)
    {
        $field_dev = [];

        $field = $request->validate([
            'waybillno' => 'nullable',
            'shipper' => 'nullable',
            'consignee' => 'nullable',
            'goingto' => 'nullable',
            'wb_description' => 'required',
            'quantity' => 'nullable|numeric',
            'unit' => 'nullable',
            'declared_value2' => 'nullable|numeric',
        ]);

        // Get customer advalorem rate
        $customer = \DB::table('str_customer')
            ->select('advalorem')
            ->where('id', $field['consignee'])
            ->first();

        $field['customer_rates'] = $customer->advalorem;
        $field['freight_charge'] = $field['declared_value2'] * $field['customer_rates'];

        // Get waybill info
        $waybill = \DB::table('sys_waybillheader')
            ->where('waybillno', $field['waybillno'])
            ->first();

        $field_dev['hwaybillnumber'] = $waybill->hwaybillnumber ?? '';
        $field_dev['consignee'] = $waybill->consignee ?? 0;
        $field_dev['shipper'] = $waybill->shipper ?? 0;
        $field_dev['posting_date'] = ($waybill && $waybill->status === 'y') ? now()->format('Y-m-d') : null;
        $field_dev['waybilldate'] = now()->format('Y-m-d');
        $field_dev['rates'] = 'advalorem';
        $field_dev['branch'] = $request->user()->branch;
        $field_dev['goingto'] = $field['goingto'];
        $field_dev['waybillno'] = $field['waybillno'];
        $field_dev['isexceed'] = '1';
        $field_dev['item_quantity'] = $field['quantity'];
        $field_dev['remaining_qty'] = $field['quantity'];
        $field_dev['stocksinwb'] = $field['quantity'];
        $field_dev['unit'] = $field['unit'];
        $field_dev['declared_value'] = $field['declared_value2'];
        $field_dev['freight_charge'] = $field['freight_charge'];
        $field_dev['item_description'] = $field['wb_description'];
        $field_dev['waybillid'] = 0;


        $field['rates'] = "advalorem";
        $field['remaining_qty'] = $field['quantity'];
        $field['total_freight_charge'] = $field['freight_charge'];
        $field['date_created'] = now()->format('Y-m-d');

        // Save to WaybillDetails
        $wbdetails = WaybillDetails::create($field);
        $field_dev['item_id'] = $wbdetails->id;

        // Insert to sys_delivery
        \DB::table('sys_delivery')->insert($field_dev);

        return ['advalorem' => $wbdetails, 'message' => 'Advalorem added successfully'];
    }


    public function savePerKilo(Request $request)
    {
        $field_dev = [];

        $field = $request->validate([
            'waybillno' => 'nullable',
            'type' => 'nullable',
            'shipper' => 'nullable',
            'consignee' => 'nullable',
            'goingto' => 'nullable',
            'wb_description' => 'required',
            'quantity' => 'required|numeric',
            'unit' => 'required',
            'declared_value2' => 'required|numeric',
            'kilo_quantity' => 'required|numeric',
        ]);

        // Get CBM customer
        $cbmCustomer = \DB::table('str_customer')
            ->select('*')
            ->where('id', $field['consignee'])
            ->first();


        // Get waybill info
        $waybill = \DB::table('sys_waybillheader')
            ->where('waybillno', $field['waybillno'])
            ->first();

        $field_dev['hwaybillnumber'] = $waybill->hwaybillnumber ?? '';
        $field_dev['consignee'] = $waybill->consignee ?? 0;
        $field_dev['shipper'] = $waybill->shipper ?? 0;
        $field_dev['posting_date'] = ($waybill && $waybill->status === 'y') ? now()->format('Y-m-d') : null;
        $field_dev['waybilldate'] = now()->format('Y-m-d');
        $field_dev['branch'] = $request->user()->branch;
        $field_dev['goingto'] = $field['goingto'];
        $field_dev['waybillno'] = $field['waybillno'];
        $field_dev['rates'] = 'kilo';
        $field_dev['isexceed'] = $field['type'] === 'prepaid' ? '0' : '1';
        $field_dev['item_quantity'] = $field['kilo_quantity'];
        $field_dev['remaining_qty'] = $field['kilo_quantity'];
        $field_dev['stocksinwb'] = $field['quantity'];
        $field_dev['unit'] = $field['unit'];
        $field_dev['declared_value'] = $field['declared_value2'];
        $field_dev['item_description'] = $field['wb_description'];
        $field_dev['waybillid'] = 0;


        $field['value_charge'] = str_replace(',', '', $cbmCustomer->value_charge);
        $field['customer_cbm'] = str_replace(',', '', $cbmCustomer->rate_cbm);
        $field['customer_min'] = str_replace(',', '', $cbmCustomer->minimum);
        $field['customer_kilo'] = $cbmCustomer->rate_kilo;

        $field['line_cv'] = $field['declared_value2'] / 1000 * $field['value_charge'];
        $field['line_fc'] = $field['line_cv'] ; // incomplete formula (total of kilo)

        $total_freight = $field['kilo_quantity'] * $field['customer_kilo'];
        $field['total_freight_charge'] = $total_freight + $field['line_cv'];

        $field['rates'] = 'kilo';
        $field['remaining_qty'] = $field['quantity'];
        $field['kilos_or_cbm'] = $field['kilo_quantity'];
        $field['date_created'] = now()->format('Y-m-d');

        // Save to WaybillDetails
        $wbdetails = WaybillDetails::create($field);
        $field_dev['item_id'] = $wbdetails->sys_wbdetailsid;

        // Insert to sys_delivery
        $delivery_id = \DB::table('sys_delivery')->insertGetId($field_dev);

        // Update the same WaybillDetails row with delivery_id
        $wbdetails->delivery_id = $delivery_id;
        $wbdetails->save();

        return ['perkilo' => $wbdetails, 'message' => 'Per Kilo added successfully'];
    }


    public function saveSpecialItem(Request $request)
    {
        $field_dev = [];

        $field = $request->validate([
            'cus_specialitem_id' => 'required',
            'waybillno' => 'nullable',
            'type' => 'nullable',
            'shipper' => 'nullable',
            'consignee' => 'nullable',
            'goingto' => 'nullable',
            'wb_description' => 'nullable',
            'quantity' => 'required|numeric',
            'unit' => 'required',
            'declared_value2' => 'required|numeric',
            'cus_specialitem_remarks' => 'required',
        ]);


        // Get special item
        $getItem = \DB::table('cus_special')
            ->select('*')
            ->where('id', $field['cus_specialitem_id'])
            ->first();


        $field['wb_description'] = $getItem->special_item;
        $field['customer_cbm'] = $getItem->rate_php;
        $field['value_charge'] = $getItem->value_charge;
        $field_dev['freight_charge'] = $field['customer_cbm'] * $field['quantity'];

        $field['freight_charge'] = $field_dev['freight_charge'];
        $field['total_freight_charge'] = $field_dev['freight_charge'];
        $field['line_cv'] = abs(($field['declared_value2'] / 1000) * $field['value_charge']);

        $field['remaining_qty'] = $field['quantity'];
        $field['kilos_or_cbm'] = $field['quantity'];
        $field['rates'] = 'per_specific_item';
        $field['agency_status'] = (ucfirst($field['type']) == "Agency")? 'y' : 'n';
        $field['date_created'] = now()->format('Y-m-d');


        // Save to WaybillDetails
        $wbdetails = WaybillDetails::create($field);
        $field_dev['item_id'] = $wbdetails->sys_wbdetailsid;

        // Get waybill info
        $waybill = \DB::table('sys_waybillheader')
            ->where('waybillno', $field['waybillno'])
            ->first();

        $field_dev['hwaybillnumber'] = $waybill->hwaybillnumber ?? '';
        $field_dev['consignee'] = $waybill->consignee ?? 0;
        $field_dev['shipper'] = $waybill->shipper ?? 0;
        $field_dev['posting_date'] = ($waybill && $waybill->status === 'y') ? now()->format('Y-m-d') : null;
        $field_dev['waybilldate'] = now()->format('Y-m-d');
        $field_dev['branch'] = $request->user()->branch;
        $field_dev['goingto'] = $field['goingto'];
        $field_dev['waybillno'] = $field['waybillno'];
        $field_dev['rates'] = 'per_specific_item';
        $field_dev['isexceed'] = '0';
        $field_dev['item_quantity'] = $field['quantity'];
        $field_dev['remaining_qty'] = $field['quantity'];
        $field_dev['stocksinwb'] = $field['quantity'];
        $field_dev['unit'] = $field['unit'];
        $field_dev['declared_value'] = $field['declared_value2'];
        $field_dev['item_description'] = $field['wb_description'];
        $field_dev['waybillid'] = 0;
        $field_dev['date_created'] = now()->format('Y-m-d');


        // Insert to sys_delivery
        $delivery_id = \DB::table('sys_delivery')->insertGetId($field_dev);

        // Update the same WaybillDetails row with delivery_id
        $wbdetails->delivery_id = $delivery_id;
        $wbdetails->save();

        return ['special_item' => $wbdetails, 'message' => 'Special Item added successfully'];
    }


    public function savePerCBM(Request $request)
    {
        $field_dev = [];

        $field = $request->validate([
            'waybillno' => 'nullable',
            'type' => 'nullable',
            'shipper' => 'nullable',
            'consignee' => 'nullable',
            'goingto' => 'nullable',
            'wb_description' => 'nullable',
            'quantity' => 'required|numeric',
            'declared_value2' => 'required|numeric',
            'account_type' => 'nullable',
        ]);

        // Get Customer
        $getCust = \DB::table('str_customer')
            ->select('*')
            ->where('id', $field['consignee'])
            ->first();


        $field['customer_cbm'] = str_replace(',', '', $getCust->rate_cbm);
        $field['value_charge'] = str_replace(',', '', $getCust->value_charge);
            $field['customer_min'] = str_replace(',', '', $getCust->minimum);

        $field['line_cv'] = abs(($field['declared_value2'] / 1000) * $field['value_charge']);
        $field['line_fv'] = abs($field['line_cv'] * 0);


        $field['remaining_qty'] = $field['quantity'];
        $field['rates'] = 'cbm';
        $field['posting_status'] = $field['account_type'] == "prepaid" ? '1' : '0';
        $field['date_created'] = now()->format('Y-m-d');


        // Save to WaybillDetails
        $percbm = WaybillDetails::create($field);
        $field_dev['item_id'] = $percbm->sys_wbdetailsid;

        // // Get waybill info
        // $waybill = \DB::table('sys_waybillheader')
        //     ->where('waybillno', $field['waybillno'])
        //     ->first();

        // $field_dev['hwaybillnumber'] = $waybill->hwaybillnumber ?? '';
        // $field_dev['consignee'] = $waybill->consignee ?? 0;
        // $field_dev['shipper'] = $waybill->shipper ?? 0;
        // $field_dev['posting_date'] = ($waybill && $waybill->status === 'y') ? now()->format('Y-m-d') : null;
        // $field_dev['waybilldate'] = now()->format('Y-m-d');
        // $field_dev['branch'] = $request->user()->branch;
        // $field_dev['goingto'] = $field['goingto'];
        // $field_dev['waybillno'] = $field['waybillno'];
        // $field_dev['rates'] = 'per_specific_item';
        // $field_dev['isexceed'] = '0';
        // $field_dev['item_quantity'] = $field['quantity'];
        // $field_dev['remaining_qty'] = $field['quantity'];
        // $field_dev['stocksinwb'] = $field['quantity'];
        // $field_dev['unit'] = $field['unit'];
        // $field_dev['declared_value'] = $field['declared_value2'];
        // $field_dev['item_description'] = $field['wb_description'];
        // $field_dev['waybillid'] = 0;
        // $field_dev['date_created'] = now()->format('Y-m-d');


        // // Insert to sys_delivery
        // $delivery_id = \DB::table('sys_delivery')->insertGetId($field_dev);

        // // Update the same WaybillDetails row with delivery_id
        // $advalorem->delivery_id = $delivery_id;
        // $advalorem->save();

        return ['percbm' => $percbm, 'message' => 'Advalorem added successfully'];
    }

}
