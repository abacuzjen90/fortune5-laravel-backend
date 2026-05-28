<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        try {
            $suppliers = Supplier::orderBy('supplier_name')->get();
            return response()->json($suppliers);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch suppliers'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:100',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:100',
            'contact_details' => 'nullable|string|max:100',
            'terms' => 'nullable|string|max:100',
            'tin' => 'nullable|string|max:100',
            'tax' => 'nullable|numeric',
            'emailaddress' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $supplier = Supplier::create($request->all());
            return response()->json($supplier, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create supplier'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|string|max:100',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:100',
            'contact_details' => 'nullable|string|max:100',
            'terms' => 'nullable|string|max:100',
            'tin' => 'nullable|string|max:100',
            'tax' => 'nullable|numeric',
            'emailaddress' => 'nullable|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $supplier->update($request->all());
            return response()->json($supplier);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update supplier'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            return response()->json(['message' => 'Supplier deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete supplier'], 500);
        }
    }
}