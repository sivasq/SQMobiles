<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryProductDetail as InventoryProductDetailResource;
use App\Inventory;
use App\InventoryProduct;
use App\InventoryProductDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;


class InventoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'supplier_id' => 'required',
            'brand_id' => 'required',
            'product_id' => 'required',
            'product_qty' => 'required',
            'purchase_price' => 'required',
            'product_serial_numbers' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();

            $inventory = Inventory::firstOrCreate(
                ['invoice_number' => $request->invoice_number],
                ['supplier_id' => $request->supplier_id]
            );

            $filtered_imei_numbers = collect($request->product_serial_numbers)->filter(function ($item) {
                return $item['imei_number'] != null;
            })->values()->toArray();

            $inventoryProduct = new InventoryProduct();
            $inventoryProduct->inventory_id = $inventory->id;
            $inventoryProduct->product_id = $request->product_id;
            $inventoryProduct->inventory_product_qty = ($request->product_qty != count($filtered_imei_numbers)) ? count
            ($filtered_imei_numbers) : $request->product_qty;
            $inventoryProduct->purchase_price_per_qty = $request->purchase_price;
            $inventoryProduct->save();

            // Inventory_imei_records to be saved
            $inventory_imei_records = [];
            foreach ($request->product_serial_numbers as $product_serial_number) {
                if (!empty($product_serial_number['imei_number'])) {
                    $inventory_imei_records[] = [
                        'inventory_product_id' => $inventoryProduct->id,
                        'imei_number' => $product_serial_number['imei_number'],
                        'branch_id' => 1
                    ];
                }
            }
            InventoryProductDetail::insert($inventory_imei_records);
            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false], 401);
        }
    }

    public function getImeiBasedStockDetails()
    {
        return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->get());

        // return InventoryProductDetailResource::collection(InventoryProductDetail::where('branch_id', auth()->user()->branch_id)->get());
    }

    public function getImeiBasedSalesDetails()
    {
        return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->get());
    }
}
