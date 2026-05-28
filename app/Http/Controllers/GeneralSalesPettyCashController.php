<?php

namespace App\Http\Controllers;

use App\Models\GeneralSalesPettyCash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class GeneralSalesPettyCashController extends Controller implements HasMiddleware
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
        $date = $request->query('date');        // ?date=11/08/2025

        $query = GeneralSalesPettyCash::query();

        if ($date) {
            $query->where('date', $date);
        }
            $query->where('encoder', $request->user()->id);

        return response()->json(
            $query->orderBy('created_at', 'asc')->get()
        );
    }


    public function getPettyCashHistory(Request $request)
    {
        $date = $request->input('date');

        $query = GeneralSalesPettyCash::query()
            ->join('users', 'users.id', '=', 'general_sales_petty_cashes.encoder')
            ->select(
                'general_sales_petty_cashes.id',
                'general_sales_petty_cashes.encoder',
                'general_sales_petty_cashes.amount',
                'general_sales_petty_cashes.created_at',
                'general_sales_petty_cashes.date',
                'users.name as encoder_name'
            );

        if ($date) {
            $query->where('general_sales_petty_cashes.date', $date);
        }

        $records = $query
            ->orderBy('general_sales_petty_cashes.encoder')
            ->orderBy('general_sales_petty_cashes.created_at')
            ->get()
            ->groupBy('encoder');

        $result = $records->map(function ($entries, $encoderId) {
            return [
                'encoder'      => $encoderId,
                'encoder_name' => $entries->first()->encoder_name,
                'entries'      => $entries->map(function ($e) {
                    return [
                        'id'         => $e->id,
                        'amount'     => $e->amount,
                        'created_at' => $e->created_at,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json($result);
    }



    public function getPettyCashEncoder(Request $request)
    {
        $date = $request->input('date');
        $encoder = $request->input('encoder');

        $query = GeneralSalesPettyCash::query();

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
            $saved[] = GeneralSalesPettyCash::create([
                'date'         => $validated['date'],
                'denomination' => $item['denomination'],
                'quantity'     => $item['quantity'] ?? 0,
                'amount'       => $item['amount'] ?? 0,
                'encoder'      => $request->user()->id,
            ]);
        }

            return response()->json([
                'message' => 'Petty cash records saved successfully',
                'data' => $saved
            ], 200);
        }


    /**
     * Display the specified resource.
     */
    public function show(GeneralSalesPettyCash $generalSalesPettyCash)
    {
        return [$generalSalesPettyCash];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralSalesPettyCash $generalSalesPettyCash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralSalesPettyCash $generalSalesPettyCash)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralSalesPettyCash $generalSalesPettyCash)
    {
        //
    }
}
