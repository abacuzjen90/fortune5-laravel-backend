<?php

namespace App\Http\Controllers;

use App\Models\IssuanceDetails;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class IssuanceDetailsController extends Controller implements HasMiddleware
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
        if (!$request->has('issuanceDetails')) {
            return response()->json([
                'message' => 'Missing issuanceDetails data'
            ], 400);
        }

        $details = $request->input('issuanceDetails');
        $createdIssuance = [];

        foreach ($details as $index => $data) {
            $validator = \Validator::make($data, [
                'issuance_id' => 'required|exists:issuance_headers,id',
                'stock_id' => 'required|integer',
                'product_id' => 'required|integer',
                'unit' => 'required|string',
                'quantity' => 'required|numeric|min:0',
                'unit_price' => 'required|numeric',
                'amount' => 'nullable|numeric',
                'status' => 'nullable|string',
                'unit_type' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => "Validation error in item #{$index}",
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();

            // Lock stock details for update
            $stock_details = \DB::table('stock_details')
                ->where('header_id', $validated['stock_id'])
                ->where('product_id', $validated['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$stock_details) {
                return response()->json([
                    'message' => "Stock detail not found for item #{$index}",
                ], 404);
            }

            // Determine adjustment
            $quantity = floatval($validated['quantity']);
            $isReturn = strtolower($validated['status']) === 'return';
            $isBigUnit = isset($validated['unit_type']) && strtoupper($validated['unit_type']) === 'BIG_UNIT';
            $unit = $stock_details->unit;

            if ($isBigUnit) {
                // Conversion factor (1 BIG = X SMALL)
                $conversion = floatval($stock_details->small_conversion ?? 1);
                $smallEquivalent = $quantity * $conversion;

                // BIG UNIT adjustment
                //$new_big_qty = $isReturn
                //    ? $stock_details->big_qty + $quantity
                //    : $stock_details->big_qty - $quantity;

                // SMALL UNIT adjustment in remaining_qty
                if ($unit){
                    $new_remaining = $isReturn
                        ? $stock_details->remaining_qty + $smallEquivalent
                        : $stock_details->remaining_qty - $smallEquivalent;

                    // Issued qty always tracked in small units
                    $new_issued = $isReturn
                        ? $stock_details->issued_qty - $smallEquivalent
                        : $stock_details->issued_qty + $smallEquivalent;
                } else {
                    $new_remaining = $isReturn
                        ? $stock_details->remaining_qty + $quantity
                        : $stock_details->remaining_qty - $quantity;

                    // Issued qty always tracked in small units
                    $new_issued = $isReturn
                        ? $stock_details->issued_qty - $quantity
                        : $stock_details->issued_qty + $quantity;
                }

                \DB::table('stock_details')
                    ->where('id', $stock_details->id)
                    ->update([
                        //'big_qty'       => $new_big_qty,
                        'remaining_qty' => $new_remaining,
                        'issued_qty'    => $new_issued,
                    ]);
            } else {
                // Normal SMALL UNIT handling
                $new_remaining = $isReturn
                    ? $stock_details->remaining_qty + $quantity
                    : $stock_details->remaining_qty - $quantity;

                $new_issued = $isReturn
                    ? $stock_details->issued_qty - $quantity
                    : $stock_details->issued_qty + $quantity;

                \DB::table('stock_details')
                    ->where('id', $stock_details->id)
                    ->update([
                        'remaining_qty' => $new_remaining,
                        'issued_qty'    => $new_issued,
                    ]);
            }

            $createdIssuance[] = IssuanceDetails::create($validated);
        }

        return response()->json([
            'IssuanceDetails' => $createdIssuance,
            'message' => 'Issuance Details added successfully'
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(IssuanceDetails $issuanceDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IssuanceDetails $issuanceDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $issuanceId)
    {
        if (!$request->has('issuanceDetails')) {
            return response()->json(['message' => 'Missing issuanceDetails data'], 400);
        }

        $details = $request->input('issuanceDetails');
        $processedIds = [];
        $updatedIssuance = [];

        \DB::beginTransaction();

        try {
            foreach ($details as $index => $data) {
                $validator = \Validator::make($data, [
                    'id' => 'nullable|integer|exists:issuance_details,id',
                    'issuance_id' => 'required|exists:issuance_headers,id',
                    'stock_id' => 'required|integer',
                    'product_id' => 'required|integer',
                    'unit' => 'required|string',
                    'quantity' => 'required|numeric|min:1',
                    'unit_price' => 'required|numeric|min:0',
                    'amount' => 'nullable|numeric|min:0',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => "Validation error in item #{$index}",
                        'errors' => $validator->errors(),
                    ], 422);
                }

                $validated = $validator->validated();
                $prevQty = 0;
                $isUpdate = false;

                if (!empty($validated['id'])) {
                    $existing = IssuanceDetails::find($validated['id']);
                    if ($existing) {
                        $prevQty = $existing->quantity;
                        $existing->update($validated);
                        $processedIds[] = $existing->id;
                        $updatedIssuance[] = $existing;
                        $isUpdate = true;
                    }
                } else {
                    $new = IssuanceDetails::create($validated);
                    $processedIds[] = $new->id;
                    $updatedIssuance[] = $new;
                }

                // Stock Update
                $stock = \DB::table('stock_details')
                    ->where('header_id', $validated['stock_id'])
                    ->where('product_id', $validated['product_id'])
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    // ✅ Update only if quantity changed
                    if (!$isUpdate || $prevQty != $validated['quantity']) {
                        \DB::table('stock_details')
                            ->where('id', $stock->id)
                            ->update([
                                'remaining_qty' => $stock->remaining_qty + $prevQty - $validated['quantity'],
                                'issued_qty' => $stock->issued_qty - $prevQty + $validated['quantity'],
                            ]);
                    }
                }
            }

            // Handle Deletions
            $toDelete = IssuanceDetails::where('issuance_id', $issuanceId)
                ->whereNotIn('id', $processedIds)
                ->get();

            foreach ($toDelete as $item) {
                // ✅ Restore stock before deleting
                $stock = \DB::table('stock_details')
                    ->where('header_id', $item->stock_id)
                    ->where('product_id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    \DB::table('stock_details')
                        ->where('id', $stock->id)
                        ->update([
                            'remaining_qty' => $stock->remaining_qty + $item->quantity,
                            'issued_qty' => $stock->issued_qty - $item->quantity,
                        ]);
                }

                $item->delete();
            }

            \DB::commit();

            return response()->json([
                'IssuanceDetails' => $updatedIssuance,
                'message' => 'Issuance Details updated successfully',
            ], 200);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => 'Server error', 'error' => $e->getMessage()], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IssuanceDetails $issuanceDetails)
    {
        //
    }


    public function getIssuanceList(Request $request)
    {
        // Validate required dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        $query = \DB::table('issuance_details')
            ->join('issuance_headers', 'issuance_details.issuance_id', '=', 'issuance_headers.id')
            ->join('stock_details', function ($join) {
                $join->on('issuance_details.stock_id', '=', 'stock_details.header_id')
                    ->whereColumn('issuance_details.product_id', 'stock_details.product_id');
            })
            ->join('inventory_items', 'issuance_details.product_id', '=', 'inventory_items.id')
            ->select(
                'issuance_details.id',
                'issuance_details.issuance_id',
                'issuance_details.stock_id',
                'issuance_details.product_id',
                'issuance_details.unit',
                'issuance_details.quantity',
                'issuance_details.unit_price',
                'issuance_details.amount',
                'issuance_details.status',
                'issuance_details.unit_type',
                'issuance_headers.id as header_id',
                'issuance_headers.drletter',
                'issuance_headers.drno',
                'issuance_headers.encoded_by',
                'issuance_headers.transaction_date',
                'issuance_headers.customer_name',
                'inventory_items.product_name',
                'stock_details.cost_per_unit',
                'stock_details.big_cost',
                'stock_details.big_price',
                'stock_details.price_per_unit',
                'stock_details.small_conversion',
            )
            ->whereBetween('issuance_headers.transaction_date', [$from, $to])
            ->orderBy('issuance_details.issuance_id', 'desc');

        $productList = $query->get();

        return response()->json($productList);
    }


    public function getIssuanceListDashboard(Request $request)
    {
        $query = \DB::table('issuance_details')
            ->join('issuance_headers', 'issuance_details.issuance_id', '=', 'issuance_headers.id')
            ->join('stock_details', function ($join) {
                $join->on('issuance_details.stock_id', '=', 'stock_details.header_id')
                    ->whereColumn('issuance_details.product_id', 'stock_details.product_id');
            })
            ->join('inventory_items', 'issuance_details.product_id', '=', 'inventory_items.id')
            ->select(
                'issuance_details.id',
                'issuance_details.issuance_id',
                'issuance_details.stock_id',
                'issuance_details.product_id',
                'issuance_details.unit',
                'issuance_details.quantity',
                'issuance_details.unit_price',
                'issuance_details.amount',
                'issuance_details.status',
                'issuance_headers.id as header_id',
                'issuance_headers.drno',
                'issuance_headers.encoded_by',
                'issuance_headers.transaction_date',
                'issuance_headers.customer_name',
                'inventory_items.product_name',
                'stock_details.cost_per_unit'
            )
            ->orderBy('issuance_details.issuance_id', 'desc');

        $productList = $query->get();

        return response()->json($productList);
    }

}
