<?php

namespace App\Http\Controllers;

use App\Models\AccountTitle;
use App\Models\AccountSub;
use Illuminate\Http\Request;

class AccountTitleController extends Controller
{
    public function index()
    {
        return AccountTitle::with(['header', 'subClass'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'saccountid' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'haccountid' => 'required|integer'
        ]);

        try {
            // Get the sub-class to get the reference number
            $subClass = AccountSub::findOrFail($validated['saccountid']);
            
            // Find the highest existing account number for this sub-class
            $lastAccount = AccountTitle::where('saccountid', $validated['saccountid'])
                ->orderBy('tsequenceno', 'desc')
                ->first();
            
            // Generate the new sequence number
            $sequenceNumber = $lastAccount ? $lastAccount->tsequenceno + 1 : 1;
            
            // Generate the CHARINO
            $refNum = str_pad($subClass->subsequenceno, 2, '0', STR_PAD_LEFT);
            $accountNum = str_pad($sequenceNumber, 3, '0', STR_PAD_LEFT);
            $chartno = $refNum . $accountNum;

           $accountTitle = AccountTitle::create([
                'haccountid' => $validated['haccountid'],
                'saccountid' => $validated['saccountid'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'tsequenceno' => $sequenceNumber,
                'chartno' => $chartno
            ]);

            // Load the relationships before returning
            $accountTitle->load(['header', 'subClass']);

            return response()->json($accountTitle, 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        return AccountTitle::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $accountTitle = AccountTitle::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'tsequenceno' => 'required|integer',
            'chartno' => 'required|string|max:20'
        ]);

        $accountTitle->update($validated);
        return response()->json($accountTitle);
    }

    public function destroy($id)
    {
        AccountTitle::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}