<?php

namespace App\Http\Controllers;

use App\Models\SpecialItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SpecialItemController extends Controller implements HasMiddleware
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
        return SpecialItem::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'customer_id' => 'required|numeric',
            'consignee_id' => 'required|numeric',
            'special_item' => 'required|string',
            'rate_php' => 'required|numeric',
            'unit' => 'required|string',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'cbm' => 'nullable|numeric',
            'kilo' => 'nullable|numeric',
            'value_charge' => 'nullable|numeric',
            'account_type' => 'nullable|string',
        ]);

        $field['cbm'] ??= 0;
        $field['kilo'] ??= 0;
        $field['value_charge'] ??= 0;

        $special_item = SpecialItem::create($field);
        return ['Special Item' => $special_item, 'message' => 'Special Item added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Item = \DB::table('cus_special')
            ->where('id', $id)->first();
        if (!$Item) {
            return response()->json(['message' => 'No record found'], 404);
        }

        return response()->json($Item, 200);
    }


    public function getSpecialItem($id)
    {
        $Item = \DB::table('cus_special')
            ->join('str_customer', 'cus_special.customer_id', '=', 'str_customer.id')->where('cus_special.customer_id', "=", $id)
            ->get('cus_special.*');
        if (!$Item) {
            return response()->json(['message' => 'Special item not found'], 404);
        }

        return response()->json($Item);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialItem $specialItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $field = $request->validate([
            'special_item' => 'required|string',
            'rate_php' => 'required|numeric',
            'unit' => 'required|string',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'cbm' => 'nullable|numeric',
            'kilo' => 'nullable|numeric',
            'value_charge' => 'nullable|numeric',
        ]);

        $field['cbm'] ??= 0;
        $field['kilo'] ??= 0;
        $field['value_charge'] ??= 0;

        $Item = \DB::table('cus_special')->where('id', $id)->update($field);

        return ['Special Item' => $Item, 'message' => 'Special Item updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Item = \DB::table('cus_special')->where('id', $id)->delete();
        return ['Special Item' => $Item, 'message' => 'Special Item deleted successfully'];
    }




    public function getCustSpecialItem(Request $request)
    {
        $keywords = $request->query('name');
        $accountType = $request->query('type');
        $customerConsignee = $request->query('consignee');
        $customerShipper = $request->query('shipper');
        $branch = str_replace(' ', '_', $request->query('branch'));

        $data = [];

        // 1. CUSTOMER SPECIAL ITEM - CONSIGNEE
        $specialItemConsignee = \DB::table('cus_special')
            ->where('customer_id', $customerConsignee)
            ->when($keywords, function ($q) use ($keywords) {
                $q->where('special_item', 'like', "%$keywords%");
            })
            ->get();

        foreach ($specialItemConsignee as $item) {
            $data[] = [
                'id' => $item->id,
                'item' => $item->special_item,
                'unit' => $item->unit,
                'rate' => $item->rate_php,
            ];
        }

        // 2. CUSTOMER SPECIAL ITEM - SHIPPER
        $specialItemShipper = \DB::table('cus_special')
            ->where('customer_id', $customerShipper)
            ->when($keywords, function ($q) use ($keywords) {
                $q->where('special_item', 'like', "%$keywords%");
            })
            ->get();

        foreach ($specialItemShipper as $item) {
            $data[] = [
                'id' => $item->id,
                'item' => $item->special_item,
                'unit' => $item->unit,
                'rate' => $item->rate_php,
            ];
        }

        // 3. BRANCH SPECIAL ITEM
        $branchItems = \DB::table('cus_special')
            ->where('customer_id', $branch)
            ->when($keywords, function ($q) use ($keywords) {
                $q->where('special_item', 'like', "%$keywords%");
            })
            ->get();

        foreach ($branchItems as $item) {
            $data[] = [
                'id' => $item->id,
                'item' => $item->special_item,
                'unit' => $item->unit,
                'rate' => $item->rate_php,
            ];
        }

        return response()->json($data);
    }

}
