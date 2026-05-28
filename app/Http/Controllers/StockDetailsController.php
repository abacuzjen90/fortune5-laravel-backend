<?php

namespace App\Http\Controllers;

use App\Models\StockDetails;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class StockDetailsController extends Controller implements HasMiddleware
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
        if (!$request->has('stockDetails')) {
            return response()->json([
                'message' => 'Missing stockDetails data'
            ], 400);
        }

        $details = $request->input('stockDetails');
        $createdStocks = [];
        $productIds = [];

        foreach ($details as $index => $data) {
            // ✅ Check for duplicate product_id
            if (in_array($data['product_id'], $productIds)) {
                return response()->json([
                    'message' => "Duplicate product_id detected in item #{$index}",
                    'errors' => ['product_id' => ["Duplicate product_id: {$data['product_id']}"]],
                ], 422);
            }
            $productIds[] = $data['product_id']; // Add to tracking list

            $validator = \Validator::make($data, [
                'header_id' => 'required|exists:stock_headers,id',
                'product_id' => 'required|integer',
                'sku' => 'required|string',
                'unit' => 'nullable|string',
                'quantity' => 'nullable|numeric|min:1',
                'cost_per_unit' => 'nullable|numeric|min:0',
                'price_per_unit' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'total_purchase_cost' => 'required|numeric|min:0',
                'common_name' => 'nullable|string',
                'big_unit' => 'nullable|string',
                'big_qty' => 'nullable|numeric',
                'big_price' => 'nullable|numeric',
                'big_cost' => 'nullable|numeric',
                'big_conversion' => 'nullable|numeric',
                'small_conversion' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => "Validation error in item #{$index}",
                    'errors' => $validator->errors(),
                ], 422);
            }

            $validated = $validator->validated();
            $validated['remaining_qty'] = $validated['quantity'] ?? $validated['big_qty'];
            $validated['issued_qty'] = 0;

            $createdStocks[] = StockDetails::create($validated);
        }

        return response()->json([
            'StockDetails' => $createdStocks,
            'message' => 'Stock Details added successfully'
        ], 201);
    }




    /**
     * Display the specified resource.
     */
    public function show(StockDetails $stockDetails, $id)
    {
        $stockDetails = StockDetails::find($id);
        return [$stockDetails];
    }

    public function getStockDetails(Request $request, $id)
    {
        return StockDetails::where('header_id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockDetails $stockDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $headerId)
    {
        // ✅ Delete removed rows
        if ($request->has('deletedIds')) {
            StockDetails::whereIn('id', $request->deletedIds)
                ->where('header_id', $headerId) // extra protection
                ->delete();
        }

        // ✅ Process stock details
        foreach ($request->stockDetails as $detail) {

            // Validate each detail (optional but recommended)
            $validated = \Validator::make($detail, [
                'product_id' => 'required|integer',
                'sku' => 'required|string',
                'unit' => 'nullable|string',
                'quantity' => 'nullable|numeric|min:1',
                'cost_per_unit' => 'nullable|numeric|min:0',
                'price_per_unit' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'total_purchase_cost' => 'required|numeric|min:0',
                'common_name' => 'nullable|string',
                'big_unit' => 'nullable|string',
                'big_qty' => 'nullable|numeric',
                'big_cost' => 'nullable|numeric',
                'big_price' => 'nullable|numeric',
                'big_conversion' => 'nullable|numeric',
                'small_conversion' => 'nullable|numeric',

            ])->validate();

            $validated['header_id'] = $headerId;
            $validated['remaining_qty'] = $validated['quantity'] ?? $validated['big_qty'];
            $validated['issued_qty'] = 0;

            if (isset($detail['id'])) {
                // ✅ Update existing row safely
                $stockDetail = StockDetails::where('id', $detail['id'])
                    ->where('header_id', $headerId) // ensures correct parent linkage
                    ->first();
                if ($stockDetail) {
                    $stockDetail->update($validated);
                }
            } else {
                // ✅ Create new row
                StockDetails::create($validated);
            }
        }

        return response()->json([
            'StockDetails' => $request->deletedIds,
            'message' => 'Stock Details added successfully'
        ], 201);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockDetails $stockDetails)
    {
        //
    }

    public function getProductList()
    {
        $productList = \DB::table('stock_details')
            ->join('stock_headers', 'stock_details.header_id', '=', 'stock_headers.id')
            ->join('inventory_items', 'stock_details.product_id', '=', 'inventory_items.id')
             ->select(
            'stock_details.id',
                'stock_details.header_id',
                'stock_details.product_id',
                //'stock_details.sku',
                'stock_details.quantity',
                'stock_details.issued_qty',
                'stock_details.remaining_qty',
                'stock_details.cost_per_unit',
                'stock_details.price_per_unit',
                'stock_details.unit',
                'stock_details.big_unit',
                'stock_details.big_qty',
                'stock_details.big_price',
                'stock_details.big_cost',
                'stock_headers.delivery_receipt',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                'inventory_items.image'
            )
            ->where('stock_details.remaining_qty', '>', 0)
            ->orderBy('stock_headers.created_at', 'desc')
            ->get();

        return response()->json($productList);
    }


    public function getProductListDashboard()
    {
        $productList = \DB::table('stock_details')
            ->join('stock_headers', 'stock_details.header_id', '=', 'stock_headers.id')
            ->join('inventory_items', 'stock_details.product_id', '=', 'inventory_items.id')
             ->select(
            'stock_details.id',
                'stock_details.header_id',
                'stock_details.product_id',
                //'stock_details.sku',
                'stock_details.quantity',
                'stock_details.issued_qty',
                'stock_details.remaining_qty',
                'stock_details.cost_per_unit',
                'stock_details.price_per_unit',
                'stock_details.unit',
                'stock_details.big_unit',
                'stock_details.big_qty',
                'stock_details.big_price',
                'stock_details.big_cost',
                'stock_headers.delivery_receipt',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                'inventory_items.image'
            )
            ->orderBy('stock_headers.created_at', 'desc')
            ->get();

        return response()->json($productList);
    }


    public function getProductSearch(Request $request)
    {
        $search = $request->query('search');

        $productList = \DB::table('stock_details')
            ->join('stock_headers', 'stock_details.header_id', '=', 'stock_headers.id')
            ->join('inventory_items', 'stock_details.product_id', '=', 'inventory_items.id')
             ->select(
            'stock_details.id',
                'stock_details.header_id',
                'stock_details.product_id',
                //'stock_details.sku',
                'stock_details.quantity',
                'stock_details.issued_qty',
                'stock_details.remaining_qty',
                'stock_details.cost_per_unit',
                'stock_details.price_per_unit',
                'stock_headers.delivery_receipt',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                \DB::raw("'stock' as source")

            )->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('inventory_items.product_name', 'like', '%' . $search . '%')
                  ->orWhere('inventory_items.sku', 'like', '%' . $search . '%')
                  ->orWhere('stock_headers.delivery_receipt', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('stock_headers.created_at', 'desc')
            ->get();


        $issuanceList = \DB::table('issuance_details')
            ->join('issuance_headers', 'issuance_details.issuance_id', '=', 'issuance_headers.id')
            ->join('inventory_items', 'issuance_details.product_id', '=', 'inventory_items.id')
            ->select(
                'issuance_details.id',
                'issuance_details.issuance_id',
                'issuance_details.stock_id',
                'issuance_details.unit',
                'issuance_details.quantity',
                'issuance_details.unit_price',
                'issuance_details.amount',
                'issuance_details.status',
                'issuance_headers.drletter',
                'issuance_headers.drno',
                'issuance_headers.customer_name',
                'issuance_headers.address',
                'issuance_headers.contact_number',
                'issuance_headers.transaction_date',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                \DB::raw("'issuance' as source")

            )->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('issuance_headers.customer_name', 'like', '%' . $search . '%')
                  ->orWhere('issuance_headers.contact_number', 'like', '%' . $search . '%')
                  ->orWhere('issuance_headers.address', 'like', '%' . $search . '%')
                  ->orWhere('issuance_headers.drletter', 'like', '%' . $search . '%')
                  ->orWhere('issuance_headers.drno', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('issuance_headers.created_at', 'desc')
            ->get();


            $results = $productList->merge($issuanceList);
            return response()->json($results);
    }


    public function getDashboardStock(Request $request)
    {
        $ids = $request->query('ids');

        $query = \DB::table('stock_details')
            ->join('stock_headers', 'stock_details.header_id', '=', 'stock_headers.id')
            ->join('inventory_items', 'stock_details.product_id', '=', 'inventory_items.id')
            ->select(
                'stock_details.product_id',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                'inventory_items.image',
                \DB::raw('SUM(stock_details.remaining_qty) as remaining_qty'),
                \DB::raw('SUM(stock_details.quantity) as total_quantity'),
                \DB::raw('SUM(stock_details.issued_qty) as total_issued_qty')
            )
            ->groupBy(
                'stock_details.product_id',
                'inventory_items.product_name',
                'inventory_items.sku',
                'inventory_items.reorder_level',
                'inventory_items.image'
            )
            ->orderBy('stock_headers.created_at', 'desc');

        if ($ids) {
            $idArray = explode(',', $ids);
            $query->whereIn('stock_details.product_id', $idArray);
        }  else {
            return response()->json([]);
        }

        $productList = $query->get();

        return response()->json($productList);
    }

}
