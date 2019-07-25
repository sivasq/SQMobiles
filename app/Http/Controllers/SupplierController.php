<?php

namespace App\Http\Controllers;

use App\Http\Resources\Supplier as SupplierResource;
use App\Inventory;
use App\Supplier;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return SupplierResource::collection($suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|unique:suppliers',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = new Supplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->save();

        if ($supplier->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function update(Request $request, supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $supplier->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->supplier_name = $request->supplier_name;
        $supplier->save();
        if ($supplier->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function destroy(supplier $supplier)
    {
        $productStock = Inventory::where('supplier_id', $supplier->id)->get()->count();

        if ($productStock == 0) {
            Supplier::destroy($supplier->id);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'false'], 200);
    }
}
