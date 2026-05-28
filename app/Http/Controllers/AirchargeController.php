<?php

namespace App\Http\Controllers;

use App\Models\Aircharge;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AirchargeController extends Controller implements HasMiddleware
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
        return Aircharge::all();
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
        $request->validate([
            'rates' => 'required|array',
            'rates.*.type' => 'nullable|string',
            'rates.*.consignee' => 'nullable|string',
            'rates.*.wtbreak' => 'nullable|string',
            'rates.*.express' => 'nullable|numeric',
            'rates.*.perishable' => 'nullable|numeric',
            'rates.*.gen_cargo' => 'nullable|numeric',
        ]);

        // Insert data into the database
        foreach ($request->rates as $rate) {
            Aircharge::create([
                'type' => $rate['type'],
                'consignee' => $rate['consignee'],
                'wtbreak' => $rate['wtbreak'],
                'express' => $rate['express'],
                'perishable' => $rate['perishable'],
                'gen_cargo' => $rate['gen_cargo'],
            ]);
        }

        return response()->json(['message' => 'Aircharge saved successfully!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    // Fetch aircharge data based on consignee (customer ID)
    $aircharge = \DB::table('sys_aircharge')
        ->join('str_customer', 'sys_aircharge.consignee', '=', 'str_customer.id')
        ->where('sys_aircharge.consignee', "=", $id)
        ->get(['sys_aircharge.*']);

    return response()->json($aircharge);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'rates' => 'required|array',
            'rates.*.wtbreak' => 'required|string',
            'rates.*.express' => 'nullable|numeric',
            'rates.*.perishable' => 'nullable|numeric',
            'rates.*.gen_cargo' => 'nullable|numeric',
            'rates.*.type' => 'required|string',
            'rates.*.consignee' => 'required|integer|exists:str_customer,id',
        ]);

        foreach ($validatedData['rates'] as $rate) {
            $updated = \DB::table('sys_aircharge')
                ->where('consignee', $rate['consignee'])
                ->where('wtbreak', $rate['wtbreak'])
                ->update([
                    'express' => $rate['express'],
                    'perishable' => $rate['perishable'],
                    'gen_cargo' => $rate['gen_cargo'],
                    'type' => $rate['type'],
                    'updated_at' => now(),
                ]);

            // If no records were updated, you can handle it (optional)
            if (!$updated) {
                return response()->json([
                    'message' => 'No matching records found to update.',
                ], 404);
            }
        }

        return response()->json([
            'message' => 'Aircharge records updated successfully.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aircharge $aircharge)
    {
        //
    }
}
