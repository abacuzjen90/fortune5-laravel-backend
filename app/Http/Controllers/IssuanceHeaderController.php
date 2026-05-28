<?php

namespace App\Http\Controllers;

use App\Models\IssuanceDetails;
use App\Models\IssuanceHeader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class IssuanceHeaderController extends Controller implements HasMiddleware
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
        //
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
            'drletter' => 'nullable|string',
            'drno' => 'nullable|string',
            'customer_name' => 'required|string',
            'address' => 'required',
            'contact_number' => 'nullable',
            'transaction_date' => 'nullable|date',
            'terms' => 'nullable|string',
            'total_quantity' => 'nullable',
            'total_amount' => 'nullable',
        ]);

        $field['encoded_by'] = $request->user()->name;

        $issuanceHeader = IssuanceHeader::create($field);
        return response()->json([
            'header_id' => $issuanceHeader->id,
            'IssuanceHeader' => $issuanceHeader,
            'message' => 'Issuance Header added successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(IssuanceHeader $issuanceHeader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IssuanceHeader $issuanceHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $field = $request->validate([
            'drletter' => 'nullable|string',
            'drno' => 'nullable|string',
            'customer_name' => 'required|string',
            'address' => 'required',
            'contact_number' => 'nullable|string',
            'transaction_date' => 'nullable|date',
            'terms' => 'nullable|string',
            'total_quantity' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric',
        ]);

        $issuanceHeader = IssuanceHeader::find($id);

        if (!$issuanceHeader) {
            return response()->json(['message' => 'Issuance Header not found.'], 404);
        }

        $issuanceHeader->update($field);

        return response()->json([
            'header_id' => $issuanceHeader->id,
            'IssuanceHeader' => $issuanceHeader,
            'message' => 'Issuance Header updated successfully',
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IssuanceHeader $issuanceHeader)
    {
        //
    }

    //get issuance details
    public function getIssuanceDetails(IssuanceHeader $issuanceHeader, $id)
    {
        $header = IssuanceHeader::find($id);
        $details = \DB::table('issuance_details')
        ->join('stock_details', function ($join) {
        $join->on('issuance_details.stock_id', '=', 'stock_details.header_id')
             ->whereColumn('issuance_details.product_id', 'stock_details.product_id');
        })
        ->join('inventory_items', 'issuance_details.product_id', '=', 'inventory_items.id')
        ->where('issuance_details.issuance_id', $id)
        ->select('issuance_details.*', 'stock_details.cost_per_unit', 'stock_details.price_per_unit', 'stock_details.big_cost', 'stock_details.big_price', 'inventory_items.product_name', 'inventory_items.sku')
        ->get();


        return [
            'header' => $header,
            'details' => $details,
        ];
    }
}
