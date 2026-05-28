<?php

namespace App\Http\Controllers;

use App\Models\AccountSub;
use Illuminate\Http\Request;

class AccountSubController extends Controller
{
    public function index()
    {
        return response()->json(AccountSub::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'haccountid' => 'required|integer',
            'subtitle' => 'required|string',
            'subsequenceno' => 'required|integer'
        ]);

        $accountSub = AccountSub::create($validated);

        return response()->json([
            'message' => 'Account sub-class created successfully',
            'data' => $accountSub
        ], 201);
    }

    public function show($id)
    {
        return response()->json(AccountSub::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $accountSub = AccountSub::findOrFail($id);

        $validated = $request->validate([
            'haccountid' => 'sometimes|required|integer',
            'subtitle' => 'sometimes|required|string',
            'subsequenceno' => 'sometimes|required|integer'
        ]);

        $accountSub->update($validated);

        return response()->json([
            'message' => 'Account sub-class updated successfully',
            'data' => $accountSub
        ]);
    }

    public function destroy($id)
    {
        AccountSub::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Account sub-class deleted successfully'
        ], 204);
    }
}