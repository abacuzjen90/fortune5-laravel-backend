<?php

namespace App\Http\Controllers;

use App\Models\GeneralSalesCashCount;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class GeneralSalesCashCountController extends Controller implements HasMiddleware
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
    public function index(Request $request)
    {
        $date = $request->query('date'); // optional ?date=11/08/2025

        $query = GeneralSalesCashCount::query();

        if ($date) {
            $query->where('date', $date);
        }
            $query->where('encoder', $request->user()->id);

        return response()->json($query->get());
    }


    public function getCashCountHistory(Request $request)
    {
        //$date = $request->query('date');        // ?date=11/08/2025
        $date = $request->input('date');

        $query = GeneralSalesCashCount::query()
            ->join('users', 'users.id', '=', 'general_sales_cash_counts.encoder')
            ->select(
                'general_sales_cash_counts.*',
                'users.name as encoder_name',
            );

        if ($date) {
            $query->where('general_sales_cash_counts.date', $date);
        }

        return response()->json($query->get());
    }


    public function getCashCountEncoder(Request $request)
    {
        $date = $request->input('date');
        $encoder = $request->input('encoder');

        $query = GeneralSalesCashCount::query();

        if ($date) {
            $query->where('date', $date);
        }
            $query->where('encoder', $encoder);

        return response()->json($query->get());
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
        $validated = $request->validate([
            'date' => 'required|string',
            'items' => 'required|array',
            'items.*.denomination' => 'required|numeric',
            'items.*.quantity' => 'nullable|numeric',
            'items.*.amount' => 'nullable|numeric',
        ]);

        $saved = [];

        foreach ($validated['items'] as $item) {
            $record = GeneralSalesCashCount::updateOrCreate(
                [
                    'date' => $validated['date'],
                    'denomination' => $item['denomination'],
                ],
                [
                    'quantity' => $item['quantity'] ?? 0,
                    'amount' => $item['amount'] ?? 0,
                    'encoder' => $request->user()->id,
                ]
            );

            $saved[] = $record;
        }

        return response()->json([
            'message' => 'Cash count records saved successfully',
            'data' => $saved
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneralSalesCashCount $generalSalesCashCount)
    {
        return [$generalSalesCashCount];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralSalesCashCount $generalSalesCashCount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralSalesCashCount $generalSalesCashCount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralSalesCashCount $generalSalesCashCount)
    {
        //
    }
}
