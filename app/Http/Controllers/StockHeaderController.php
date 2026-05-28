<?php

namespace App\Http\Controllers;

use App\Models\StockHeader;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class StockHeaderController extends Controller implements HasMiddleware
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
        return StockHeader::latest()->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'supplier_name' => 'required|string',
            'delivery_receipt' => 'required|string',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:order_date',
            'remarks' => 'nullable|string',
        ]);

        $field['encoded_by'] = $request->user()->name;

        $stockHeader = StockHeader::create($field);
        return response()->json([
            'header_id' => $stockHeader->id,
            'StockHeader' => $stockHeader,
            'message' => 'Stock Header added successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StockHeader $stockHeader, $id)
    {
        $stockHeader = StockHeader::find($id);
        return [$stockHeader];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockHeader $stockHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockHeader $stockHeader, $id)
    {
        $stockHeader = StockHeader::find($id);

        $field = $request->validate([
            'supplier_name' => 'required|string',
            'delivery_receipt' => 'required|string',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:order_date',
            'remarks' => 'nullable|string',
        ]);

        $field['encoded_by'] = $request->user()->name;

        $stockHeader->update($field);
        return [$stockHeader];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockHeader $stockHeader, $id)
    {
        $stockHeader = StockHeader::find($id);
        $stockHeader->delete();
        return ['message' => 'Stock was deleted!'];
    }
}
