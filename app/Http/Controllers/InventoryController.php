<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryProductDetail as InventoryProductDetailResource;
use App\Inventory;
use App\InventoryProduct;
use App\InventoryProductDetail;
use Illuminate\Http\Request;
use Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
            'branch_id' => 'required',
            'brand_id' => 'required',
            'product_id' => 'required',
            'product_qty' => 'required',
            'product_serial_numbers' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $inventory = Inventory::firstOrCreate(
            ['invoice_number' => $request->invoice_number],
            ['supplier_id' => $request->supplier_id]
        );

        //        $inventory = new Inventory();
        //        $inventory->invoice_number = $request->invoice_number;
        //        $inventory->supplier_id = $request->supplier_id;
        //        $inventory->save();

        $inventoryProduct = new InventoryProduct();
        $inventoryProduct->inventory_id = $inventory->id;
        $inventoryProduct->product_id = $request->product_id;
        $inventoryProduct->qty = ($request->product_qty != count($request->product_serial_numbers)) ? count
        ($request->product_serial_numbers) : $request->product_qty;
        $inventoryProduct->save();

        // Book records to be saved
        $inventory_imei_records = [];
        foreach ($request->product_serial_numbers as $product_serial_number) {
            if (!empty($product_serial_number['imei_number'])) {
                $inventory_imei_records[] = [
                    'inventory_product_id' => $inventoryProduct->id,
                    'imei_number' => $product_serial_number['imei_number'],
                    'branch_id' => 0
                ];
            }
        }
        InventoryProductDetail::insert($inventory_imei_records);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\stock $stock
     * @return \Illuminate\Http\Response
     */
    public function show(stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\stock $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\stock $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\stock $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy(stock $stock)
    {
        //
    }

    public function getInventoryProductDetails()
    {
        return InventoryProductDetailResource::collection(InventoryProductDetail::where('branch_id', auth()->user()->branch_id)->get());
    }
}
