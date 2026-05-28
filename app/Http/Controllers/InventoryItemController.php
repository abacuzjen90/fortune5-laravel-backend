<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventoryItemController extends Controller implements HasMiddleware
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
        return InventoryItem::orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $field = $request->validate([
    //         'product_name'   => 'required|string|unique:inventory_items,product_name',
    //         'sku'            => 'required|string',
    //         'cost_per_unit'  => 'required|numeric',
    //         'reorder_level'  => 'required|numeric',
    //         'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     // If image is uploaded
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('inventory_images', 'public');
    //         $field['image'] = $imagePath; // save path into DB
    //     }

    //     $inventoryItem = InventoryItem::create($field);

    //     return response()->json([
    //         'item' => $inventoryItem,
    //         'message' => 'Item added successfully'
    //     ]);
    // }


    public function addInventoryItem(Request $request)
    {
        $request->validate([
            'product_name'   => 'required|string',
            'sku'            => 'required|string',
            'cost_per_unit'  => 'required|numeric',
            'reorder_level'  => 'required|numeric',
            'image'          => 'nullable|string', // 👈 CHANGED
        ]);

        DB::beginTransaction();

        try {

            DB::table('inventory_items')->insert([
                'image' => $request->image, // 👈 now stores S3 path
                'product_name' => $request->product_name,
                'sku' => $request->sku,
                'cost_per_unit' => $request->cost_per_unit,
                'reorder_level' => $request->reorder_level,
                'encoded_by' => $request->user()->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Inventory item added successfully',
                'url' => $request->image
                    ? env('AWS_URL') . '/' . $request->image
                    : null,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(InventoryItem $inventoryItem, $id)
    {
        $inventoryItem = InventoryItem::find($id);
        return [$inventoryItem];
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, InventoryItem $inventoryItem, $id)
    // {
    //     $field = $request->validate([
    //         'product_name' => 'required|string',
    //         'sku' => 'required|string',
    //         'cost_per_unit' => 'required|numeric',
    //         'reorder_level' => 'required|numeric',
    //     ]);

    //     $inventoryItem = InventoryItem::find($id);
    //     $inventoryItem->update($field);

    //     return ['Item' => $inventoryItem, 'message' => 'Item updated successfully'];
    // }


    public function updateInventoryItem(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $validated = $request->validate([
            'product_name'  => 'required|string|unique:inventory_items,product_name,' . $id,
            'sku'           => 'required|string',
            'cost_per_unit' => 'required|numeric',
            'reorder_level' => 'required|numeric',
            'image'         => 'nullable|string', // 👈 CHANGED
        ]);

        $validated['encoded_by'] = $request->user()->name;

        DB::beginTransaction();

        try {
            $oldImage = $item->image;

            // 👇 If new image path is provided AND different from old
            if (!empty($request->image) && $request->image !== $oldImage) {
                $validated['image'] = $request->image;

                // 🔥 delete old image from S3
                if (!empty($oldImage)) {
                    try {
                        Storage::disk('s3')->delete($oldImage);
                    } catch (\Exception $e) {
                        // ignore delete errors (optional log)
                    }
                }
            } else {
                // 👇 keep old image if not changed
                $validated['image'] = $oldImage;
            }

            // update record
            $item->update($validated);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'item' => $item,
                'url' => $item->image
                    ? env('AWS_URL') . '/' . $item->image
                    : null,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // 🔥 OPTIONAL CLEANUP (if new image uploaded but DB failed)
            if (!empty($request->image) && $request->image !== $oldImage) {
                try {
                    Storage::disk('s3')->delete($request->image);
                } catch (\Exception $cleanupError) {
                    // ignore
                }
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryItem $inventoryItem, $id)
    {
        $inventoryItem = InventoryItem::find($id);
        $inventoryItem->delete();
        return ['message' => 'Item was deleted!'];
    }


    public function uploadImageItem(Request $request)
    {
        try {
            $request->validate([
                'file_name' => 'required|string',
                'file_type' => 'required|string',
            ]);

            // Allow only images
            if (!str_starts_with($request->file_type, 'image/')) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Only image files allowed'
                ], 400);
            }

            $fileName = time() . '_' . Str::random(10) . '_' . $request->file_name;
            $path = "images/hardware/" . $fileName;

            $disk = Storage::disk('s3');
            $client = $disk->getClient();

            $command = $client->getCommand('PutObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $path,
                'ContentType' => $request->file_type,
            ]);

            $presignedRequest = $client->createPresignedRequest($command, '+5 minutes');

            return response()->json([
                'status' => 1,
                'upload_url' => (string) $presignedRequest->getUri(),
                'path' => $path,
                'url' => env('AWS_URL') . '/' . $path
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
