<?php

namespace App\Http\Controllers;

use App\Models\AccountHeader;
use Illuminate\Http\Request;

class AccountHeaderController extends Controller
{
    public function index()
    {
        return AccountHeader::all();
    }

    public function store(Request $request)
    {
        \Log::info('Incoming request', $request->all());

        $validated = $request->validate([
            'description' => 'required|string|max:100',
            'hsno' => 'required|integer'
        ]);

        $accountHeader = AccountHeader::create($validated);

        return response()->json($accountHeader, 201);
    }

    public function show(AccountHeader $accountHeader)
    {
        return $accountHeader;
    }

    public function update(Request $request, AccountHeader $accountHeader)
    {
        $request->validate([
            'description' => 'required|string|max:100',
            'hsno' => 'required|integer'
        ]);

        $accountHeader->update($request->all());

        return $accountHeader;
    }

    public function destroy(AccountHeader $accountHeader)
    {
        $accountHeader->delete();

        return response()->noContent();
    }
}