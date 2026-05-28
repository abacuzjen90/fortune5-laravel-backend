<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class CustomerController extends Controller implements HasMiddleware
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
        return Customer::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            //'cust_uniq_id' => 'required',
            'registered_name' => 'required|string|unique:str_customer,registered_name',
            'charge_to' => 'required',
            'tin_number' => 'nullable',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'required',
            'destination' => 'required',
            'value_charge' => 'nullable|numeric',
            'terms' => 'nullable',
            'rate_cbm' => 'nullable',
            'rate_kilo' => 'nullable|numeric',
            'airvalue' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'small_rate' => 'nullable|numeric',
            'medium_rate' => 'nullable|numeric',
            'large_rate' => 'nullable|numeric',
            'parcel_rate' => 'nullable|numeric',
            'account_type' => 'required',
            'agency_type' => 'nullable',
            'vat' => 'nullable',
            'applicable_tax' => 'nullable|numeric',
            'fcl_value_charge' => 'nullable|numeric',
            'ftr10' => 'nullable|numeric',
            'ftr20' => 'nullable|numeric',
            'ftr40' => 'nullable|numeric',
            'ftr20_flat' => 'nullable|numeric',
            'ftr40_flat' => 'nullable|numeric',
            'wheeler4' => 'nullable|numeric',
            'wheeler6' => 'nullable|numeric',
            'wheeler8' => 'nullable|numeric',
            'wheeler10' => 'nullable|numeric',
            'freightliner' => 'nullable|numeric',
            'rolling_cargo' => 'nullable|numeric',
            'ftr10_value' => 'nullable|numeric',
            'ftr20_value' => 'nullable|numeric',
            'ftr40_value' => 'nullable|numeric',
            'ftr20_flat_value' => 'nullable|numeric',
            'ftr40_flat_value' => 'nullable|numeric',
            'wheeler4_value' => 'nullable|numeric',
            'wheeler6_value' => 'nullable|numeric',
            'wheeler8_value' => 'nullable|numeric',
            'wheeler10_value' => 'nullable|numeric',
            'freightliner_value' => 'nullable|numeric',
            'rolling_cargo_value' => 'nullable|numeric',
            'reason' => 'nullable',
            'pickup_charge_remarks' => 'nullable',
            'customer_dr_attachment' => 'nullable',
            'rates_to_apply' => 'nullable',
            'disabled_encoder' => 'nullable|numeric',
            'date_disabled' => 'nullable',
            'status' => 'nullable',
            'blacklist_status' => 'nullable',
            'date_blacklisted' => 'nullable',
            'old_status' => 'nullable',
            'verify' => 'nullable|numeric',
            'rate_status' => 'nullable',
            'rate_status_time' => 'nullable',
            'rate_status_date' => 'nullable',
            'rate_status_encoder' => 'nullable',
            'blocklist' => 'nullable|numeric',
            'encoded' => 'nullable',
            'encoder' => 'nullable|numeric',
            'user_updated' => 'nullable|numeric',
            'deactive_by' => 'nullable|numeric',
            'blacklisted_by' => 'nullable|numeric',
            'update_rate_user' => 'nullable',
            'update_rate_time_date' => 'nullable',
        ]);

        $field['cust_uniq_id'] = '0';
        $field['status'] = 'n';
        $field['verify'] = '0';
        $field['blocklist'] = '0';
        $field['encoder'] = $request->user()->id;

        $customer = Customer::create($field);
        return ['customer' => $customer, 'message' => 'Customer added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return [$customer];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $field = $request->validate([
            //'cust_uniq_id' => 'required',
            'registered_name' => 'required',
            'charge_to' => 'required',
            'tin_number' => 'nullable',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'required',
            'destination' => 'required',
            'value_charge' => 'nullable|numeric',
            'terms' => 'nullable',
            'rate_cbm' => 'nullable',
            'rate_kilo' => 'nullable|numeric',
            'airvalue' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'small_rate' => 'nullable|numeric',
            'medium_rate' => 'nullable|numeric',
            'large_rate' => 'nullable|numeric',
            'parcel_rate' => 'nullable|numeric',
            'account_type' => 'required',
            'agency_type' => 'nullable',
            'vat' => 'nullable',
            'applicable_tax' => 'nullable|numeric',
            'fcl_value_charge' => 'nullable|numeric',
            'ftr10' => 'nullable|numeric',
            'ftr20' => 'nullable|numeric',
            'ftr40' => 'nullable|numeric',
            'ftr20_flat' => 'nullable|numeric',
            'ftr40_flat' => 'nullable|numeric',
            'wheeler4' => 'nullable|numeric',
            'wheeler6' => 'nullable|numeric',
            'wheeler8' => 'nullable|numeric',
            'wheeler10' => 'nullable|numeric',
            'freightliner' => 'nullable|numeric',
            'rolling_cargo' => 'nullable|numeric',
            'ftr10_value' => 'nullable|numeric',
            'ftr20_value' => 'nullable|numeric',
            'ftr40_value' => 'nullable|numeric',
            'ftr20_flat_value' => 'nullable|numeric',
            'ftr40_flat_value' => 'nullable|numeric',
            'wheeler4_value' => 'nullable|numeric',
            'wheeler6_value' => 'nullable|numeric',
            'wheeler8_value' => 'nullable|numeric',
            'wheeler10_value' => 'nullable|numeric',
            'freightliner_value' => 'nullable|numeric',
            'rolling_cargo_value' => 'nullable|numeric',
            'reason' => 'nullable',
            'pickup_charge_remarks' => 'nullable',
            'customer_dr_attachment' => 'nullable',
            'rates_to_apply' => 'nullable',
            'disabled_encoder' => 'nullable|numeric',
            'date_disabled' => 'nullable',
            'status' => 'nullable',
            'blacklist_status' => 'nullable',
            'date_blacklisted' => 'nullable',
            'old_status' => 'nullable',
            'verify' => 'nullable|numeric',
            'rate_status' => 'nullable',
            'rate_status_time' => 'nullable',
            'rate_status_date' => 'nullable',
            'rate_status_encoder' => 'nullable',
            'blocklist' => 'nullable|numeric',
            'encoded' => 'nullable',
            'encoder' => 'nullable|numeric',
            'user_updated' => 'nullable|numeric',
            'deactive_by' => 'nullable|numeric',
            'blacklisted_by' => 'nullable|numeric',
            'update_rate_user' => 'nullable',
            'update_rate_time_date' => 'nullable',
        ]);
        $field['cust_uniq_id'] = '0';
        $field['encoder'] = $request->user()->id;

        $customer->update($field);

        return [$customer];
    }


    public function getVerifyCustomer()
    {
        $customer_type = ["account", "collect", "prepaid", "servicecargo"];

        $customer = \DB::table('str_customer')
            ->where('verify', '0' )
            ->whereIn('account_type', $customer_type)
            ->get();

        if ($customer->isEmpty()) {
            return response()->json(['message' => 'No record found'], 404);
        }

        return response()->json($customer, 200);
    }


    public function updateVerifyCustomer(Request $request)
    {
        $ids = $request->input('ids');

        $customers = Customer::whereIn('id', $ids)->get();
        if ($customers->isEmpty()) {
            return response()->json(['message' => 'Error: Please select customer/s.'], 404);
        }
        foreach ($customers as $customer) {
            $customer->verify = '1';
            $customer->save();
        }

        return response()->json([
            'message' => 'Customers verified successfully.'
            //'verified_ids' => $customers->pluck('id')
        ]);
    }

    public function deleteVerifyCustomer(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return response()->json(['message' => 'Error: Please select customer/s.'], 404);
        }

        $customers = Customer::whereIn('id', $ids)->get();
        if ($customers->isEmpty()) {
            return response()->json(['message' => 'No customers found to delete.'], 404);
        }
        Customer::whereIn('id', $ids)->delete();

        return response()->json([
            'message' => 'Customers deleted successfully.',
            // 'deleted_ids' => $ids
        ]);
    }



    public function getCustomersByBranch(Request $request)
    {
        $branchId = $request->query('branchId');
        $accountType = $request->query('accountType');

        $query = Customer::where('verify', 1)
                        ->where('status', 'n');

        // Apply branch_id condition only if accountType is NOT "Prepaid"
        if ($accountType !== 'Prepaid' && !empty($branchId)) {
            $query->where('branch_id', $branchId);
        }

        if (!empty($accountType)) {
            $query->where('account_type', $accountType);
        }
        $customers = $query->get();

        return response()->json($customers, 200);
    }


    public function getCustomersByPrepaid(Request $request)
    {
        $customerName = $request->query('customerName');

        $query = Customer::where('account_type', "Prepaid")
                        ->where('verify', 1)
                        ->where('status', 'n');
        if (!empty($customerName)) {
            $query->where('registered_name', 'like', "%{$customerName}%");
        }
        $customers = $query->get();

        return response()->json($customers, 200);
    }


    public function updateBlacklistStatus(Request $request, Customer $customer)
    {
        $customerId = $request->input('customer_id');
        $blacklistStatus = $request->input('blacklist_status');
        $userId = $request->user()->id;

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->update([
            'blacklist_status' => $blacklistStatus,
            'date_blacklisted' => now()->format('Y-m-d'),
            'blacklisted_by' => $userId,
        ]);

        return response()->json(['message' => 'Customer blacklist status updated successfully'], 200);
    }


    public function updateStatus(Request $request, Customer $customer)
    {
        $customerId = $request->input('customer_id');
        $Status = $request->input('status');
        $reason = $request->input('reason');
        $userId = $request->user()->id;

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->update([
            'status' => $Status,
            'date_disabled' => now()->format('Y-m-d'),
            'disabled_encoder' => $userId,
            'reason' => $reason,
        ]);

        return response()->json(['message' => 'Customer status updated successfully'], 200);
    }


    public function addCustomerConsignee(Request $request)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            'cust_uniq_id' => 'required',
            'registered_name' => 'required|string|unique:str_customer,registered_name',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'nullable',
            'value_charge' => 'nullable|numeric',
            'rate_cbm' => 'nullable',
            'rate_kilo' => 'nullable|numeric',
            'airvalue' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'account_type' => 'required',
            'fcl_value_charge' => 'nullable|numeric',
            'ftr10' => 'nullable|numeric',
            'ftr20' => 'nullable|numeric',
            'ftr40' => 'nullable|numeric',
            'ftr20_flat' => 'nullable|numeric',
            'ftr40_flat' => 'nullable|numeric',
            'wheeler4' => 'nullable|numeric',
            'wheeler6' => 'nullable|numeric',
            'wheeler8' => 'nullable|numeric',
            'wheeler10' => 'nullable|numeric',
            'freightliner' => 'nullable|numeric',
            'rolling_cargo' => 'nullable|numeric',
            'ftr10_value' => 'nullable|numeric',
            'ftr20_value' => 'nullable|numeric',
            'ftr40_value' => 'nullable|numeric',
            'ftr20_flat_value' => 'nullable|numeric',
            'ftr40_flat_value' => 'nullable|numeric',
            'wheeler4_value' => 'nullable|numeric',
            'wheeler6_value' => 'nullable|numeric',
            'wheeler8_value' => 'nullable|numeric',
            'wheeler10_value' => 'nullable|numeric',
            'freightliner_value' => 'nullable|numeric',
            'rolling_cargo_value' => 'nullable|numeric',
            'blocklist' => 'nullable',
            'status' => 'nullable',
            'verify' => 'nullable|numeric',
            'encoded' => 'nullable',
            'encoder' => 'nullable|numeric',
        ]);

        $field['blocklist'] = '0';
        $field['verify'] = '1';
        $field['charge_to'] = "";
        $field['encoder'] = $request->user()->id;

        $customer = Customer::create($field);
        return ['customer' => $customer, 'message' => 'Customer added successfully'];
    }

    public function updateCustomerConsignee(Request $request, $id)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            'cust_uniq_id' => 'required',
            'registered_name' => 'required',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'nullable',
            'value_charge' => 'nullable|numeric',
            'rate_cbm' => 'nullable',
            'rate_kilo' => 'nullable|numeric',
            'airvalue' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'account_type' => 'required',
            'fcl_value_charge' => 'nullable|numeric',
            'ftr10' => 'nullable|numeric',
            'ftr20' => 'nullable|numeric',
            'ftr40' => 'nullable|numeric',
            'ftr20_flat' => 'nullable|numeric',
            'ftr40_flat' => 'nullable|numeric',
            'wheeler4' => 'nullable|numeric',
            'wheeler6' => 'nullable|numeric',
            'wheeler8' => 'nullable|numeric',
            'wheeler10' => 'nullable|numeric',
            'freightliner' => 'nullable|numeric',
            'rolling_cargo' => 'nullable|numeric',
            'ftr10_value' => 'nullable|numeric',
            'ftr20_value' => 'nullable|numeric',
            'ftr40_value' => 'nullable|numeric',
            'ftr20_flat_value' => 'nullable|numeric',
            'ftr40_flat_value' => 'nullable|numeric',
            'wheeler4_value' => 'nullable|numeric',
            'wheeler6_value' => 'nullable|numeric',
            'wheeler8_value' => 'nullable|numeric',
            'wheeler10_value' => 'nullable|numeric',
            'freightliner_value' => 'nullable|numeric',
            'rolling_cargo_value' => 'nullable|numeric',
            'blocklist' => 'nullable',
            'status' => 'nullable',
            'verify' => 'nullable|numeric',
            'encoded' => 'nullable',
            'encoder' => 'nullable|numeric',
        ]);

        $field['verify'] = '1';
        $field['charge_to'] = "";
        $field['encoder'] = $request->user()->id;

        $customer = Customer::find( $id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        $customer->update($field);

        return ['customer' => $customer, 'message' => 'Customer updated successfully'];
    }



    public function getCustomerConsignee($id)
    {
    $consignee = \DB::table('str_customer')
        ->where('cust_uniq_id', $id)
        ->where('account_type', '!=', 'Prepaid')
        ->get();

    if ($consignee->isEmpty()) {
        return response()->json(['message' => 'No record found'], 404);
    }

    return response()->json($consignee, 200);
    }


    public function getCustomerShipper($id)
    {
    $customer_type = ["customer_shipper", "customer_prepaid_consignee", "customer_consignee"];

    $consignee = \DB::table('str_customer')
        ->join('users', 'str_customer.encoder', '=', 'users.id')
        ->where('cust_uniq_id', $id)
        ->whereIn('account_type', $customer_type)
        ->get(['str_customer.*', 'users.name']);

    if ($consignee->isEmpty()) {
        return response()->json(['message' => 'No record found'], 404);
    }

    return response()->json($consignee, 200);
    }


    public function addCustomerShipper(Request $request)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            'cust_uniq_id' => 'required',
            'registered_name' => 'required|string|unique:str_customer,registered_name',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'nullable',
            'pickup_charge_remarks' => 'nullable|numeric',
            'customer_dr_attachment' => 'nullable',
            'account_type' => 'required',
            'status' => 'nullable',
            'verify' => 'nullable|numeric',
            'encoder' => 'nullable|numeric',
        ]);

        $field['verify'] = '1';
        $field['status'] = 'n';
        $field['charge_to'] = "";
        $field['encoder'] = $request->user()->id;

        $customer = Customer::create($field);
        return ['customer' => $customer, 'message' => 'Customer added successfully'];
    }


    public function updateCustomerShipper(Request $request, $id)
    {
        $field = $request->validate([
            'cust_uniq_id' => 'required',
            'registered_name' => 'required',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'pickup_charge_remarks' => 'nullable',
            'customer_dr_attachment' => 'nullable',
            'encoder' => 'nullable|numeric',
        ]);
        $field['charge_to'] = "";
        $field['encoder'] = $request->user()->id;

        $customer = Customer::find( $id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }
        $customer->update($field);

        return ['customer' => $customer, 'message' => 'Customer updated successfully'];
    }


    public function updateCustomerRates(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'rates' => 'required|array',
            'rates.*.rate_cbm' => 'nullable|numeric',
            'rates.*.rate_kilo' => 'nullable|numeric',
            'rates.*.advalorem' => 'nullable|numeric',
            'rates.*.value_charge' => 'nullable|numeric',
            'rates.*.minimum' => 'nullable|numeric',
            'rates.*.cust_id' => 'required|integer|exists:str_customer,id',
        ]);

        foreach ($validatedData['rates'] as $rate) {
            $updated = \DB::table('str_customer')
                ->where('id', $rate['cust_id'])
                ->update([
                    'rate_cbm' => $rate['rate_cbm'],
                    'rate_kilo' => $rate['rate_kilo'],
                    'advalorem' => $rate['advalorem'],
                    'value_charge' => $rate['value_charge'],
                    'minimum' => $rate['minimum'],
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'message' => 'No matching records found to update.',
                ], 404);
            }
        }

        return response()->json([
            'message' => 'Customer rates updated successfully.',
        ], 200);
    }


    public function addCustomerWaybillShipper(Request $request)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            'registered_name' => 'required|string|unique:str_customer,registered_name',
            'cust_uniq_id' => 'required',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'nullable',
            'account_type' => 'nullable',
            'status' => 'nullable',
            'verify' => 'nullable|numeric',
            'charge_to' => 'nullable',
        ]);

        $field['account_type'] = 'customer_shipper';
        $field['verify'] = '1';
        $field['status'] = 'n';
        $field['charge_to'] = "";

        $customer = Customer::create($field);
        return ['customer' => $customer, 'message' => 'Customer added successfully'];
    }


    public function addCustomerWaybillConsignee(Request $request)
    {
        $request->merge([
            'registered_name' => strtoupper($request->registered_name),
        ]);

        $field = $request->validate([
            'registered_name' => 'required|string|unique:str_customer,registered_name',
            'cust_uniq_id' => 'required',
            'contact_person' => 'nullable',
            'address' => 'required',
            'mobile_number' => 'required',
            'contact_number' => 'nullable',
            'branch_id' => 'required',
            'account_type' => 'nullable',
            'rate_cbm' => 'nullable|numeric',
            'rate_kilo' => 'nullable|numeric',
            'value_charge' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'status' => 'nullable',
            'verify' => 'nullable|numeric',
            'charge_to' => 'nullable',
            'encoder' => 'nullable',
            'encoded' => 'nullable',
        ]);

        $field['account_type'] = ($field['account_type'] === 'prepaid') ? 'customer_prepaid_consignee' : 'customer_consignee';
        $field['verify'] = '1';
        $field['status'] = 'n';
        $field['charge_to'] = "";
        $field['encoder'] = $request->user()->id;
        $field['encoded'] = $request->user()->branch;

        $customer = Customer::create($field);
        return ['customer' => $customer, 'message' => 'Customer added successfully'];
    }


    public function getWaybillShipper($id = null)
    {
        if (empty($id)) {
            return response()->json([], 200);
        }

        $query = Customer::query()
            ->where('verify', 1)
            ->where('status', 'n');

        if (in_array($id, ['collect', 'servicecargo'])) {
            $query->where('account_type', 'customer_shipper');
        } else {
            $query->where('account_type', $id);
        }

        $records = $query->get();

        return response()->json($records, 200);
    }


    public function getWaybillConsignee($id = null)
    {
        if (empty($id)) {
            return response()->json([], 200);
        }

        $query = Customer::query()
            ->where('verify', 1)
            ->where('status', 'n');

        if ($id === 'account') {
            $query->where('account_type', 'customer_consignee');
        } elseif (in_array($id, ['collect', 'servicecargo'])) {
            $query->where('account_type', $id);
        } else {
            $query->where('account_type', 'customer_prepaid_consignee');
        }

        $records = $query->get();

        return response()->json($records, 200);
    }


    public function getConsigneeShipper(Request $request, $id = null)
    {
        if (empty($id)) {
            return response()->json([], 200);
        }

        $type = $request->query('type');

        $query = Customer::query()
            ->where('cust_uniq_id', $id)
            ->where('verify', 1)
            ->where('status', 'n');

        if (in_array($type, ['collect', 'servicecargo'])) {
            $query->where('account_type', 'customer_shipper');
        } else {
            $query->where('account_type', $type);
        }

        $records = $query->get();

        return response()->json($records, 200);
    }


    public function getShipperConsignee(Request $request, $id = null)
    {
        if (empty($id)) {
            return response()->json([], 200);
        }

        $type = $request->query('type');

        $query = Customer::query()
            ->where('cust_uniq_id', $id)
            ->where('verify', 1)
            ->where('status', 'n');

        if ($type === 'account') {
            $query->where('account_type', 'customer_consignee');
        } elseif (in_array($type, ['collect', 'servicecargo'])) {
            $query->where('account_type', $type);
        } else {
            $query->where('account_type', 'customer_prepaid_consignee');
        }

        $records = $query->get();

        return response()->json($records, 200);
    }
}
