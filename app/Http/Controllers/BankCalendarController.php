<?php

namespace App\Http\Controllers;

use App\Models\BankCalendar;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Carbon\Carbon;

class BankCalendarController extends Controller implements HasMiddleware
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
        return BankCalendar::orderBy('bank', 'asc')
                        ->orderBy('amount', 'asc')
                        ->get();
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
            'banks' => 'required|array|min:1',
            'banks.*.date' => 'required|date',
            'banks.*.bank' => 'required|string',
            'banks.*.checkno' => 'required|string',
            'banks.*.payee' => 'required|string',
            'banks.*.amount' => 'required|numeric',
            'banks.*.status' => 'nullable|string',
        ]);

        $created = [];

        foreach ($validated['banks'] as $bankData) {
            $bankData['date'] = Carbon::createFromFormat('m/d/Y', $bankData['date'])->format('Y-m-d');
            if (!isset($bankData['replace_id'])) {
                $bankData['replace_id'] = 0;
            }

            $created[] = BankCalendar::create($bankData);
        }

        return response()->json($created, 201);
    }


    public function storeCheckReplaced(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'replace_id' => 'nullable|integer',
            'bank' => 'required|string',
            'checkno' => 'required|string',
            'payee' => 'required|string',
            'amount' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        // Convert MM-dd-yyyy → yyyy-MM-dd
        if (!empty($validated['date'])) {
            $validated['date'] = Carbon::createFromFormat('m/d/Y', $validated['date'])->format('Y-m-d');
        }

        $existing = null;

        if (!empty($validated['replace_id'])) {
            $existing = BankCalendar::where('replace_id', $validated['replace_id'])->first();
        }

        if ($existing) {
            $existing->update($validated);
            return response()->json([
                'message' => 'Bank check replacement updated successfully.',
                'data' => $existing
            ], 200);
        } else {
            $created = BankCalendar::create($validated);
            return response()->json([
                'message' => 'Bank check replacement created successfully.',
                'data' => $created
            ], 201);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(BankCalendar $bankCalendar)
    {
        return [$bankCalendar];
    }


    public function bankCheckReplaced(BankCalendar $bankCalendar, $id)
    {
        $bank = BankCalendar::find($id);
        return [$bank];
    }


    public function bankCheckReplacedId(BankCalendar $bankCalendar, $id)
    {
        $bank = BankCalendar::where('replace_id', $id)->first();
        if ($bank) {
            return response()->json($bank);
        } else {
            return response()->json(null); // or return an empty object: response()->json((object)[])
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankCalendar $bankCalendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankCalendar $bankCalendar, $id)
    {
        // Find the record by ID or return 404
        $bank = BankCalendar::findOrFail($id);

        // Validate incoming request (only allow status for now)
        $validated = $request->validate([
            'status' => 'required|string|max:255',
        ]);

        // Update the record
        $bank->update($validated);

        // Return updated record
        return response()->json([
            'message' => 'Status updated successfully',
            'bank' => $bank
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankCalendar $bankCalendar, $id)
    {
        $bank = BankCalendar::findOrFail($id);
        $bank->delete();

        return ['message' => 'Bank check was deleted!'];
    }


    public function getBankReport(Request $request)
    {
        // Validate required dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        $query = BankCalendar::whereBetween('date', [$from, $to])->get();

        return response()->json($query);
    }


    public function getBankResultDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->input('date'));

        $query = BankCalendar::whereDate('date', $date)
            ->orWhereDate('date', $date->copy()->subDay())
            ->orWhereDate('date', $date->copy()->addDay())
            ->orderBy('date')
            ->get();

        return response()->json($query);
    }

}
