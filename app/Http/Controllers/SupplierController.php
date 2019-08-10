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
        $suppliers = Supplier::withTrashed()->get();
        return SupplierResource::collection($suppliers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|unique:suppliers',
            'gstin' => 'required|unique:suppliers',
            'email' => 'sometimes|unique:suppliers|nullable',
            'phone' => 'required|unique:suppliers',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = new Supplier();
        $supplier->supplier_name = $request->supplier_name;
        $supplier->gstin = $request->gstin;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->save();

        if ($supplier->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function update(Request $request, supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required|unique:suppliers,supplier_name,' . $supplier->id,
            'gstin' => 'required|unique:suppliers,supplier_name,' . $supplier->id,
            'email' => 'sometimes|nullable|unique:suppliers,supplier_name,' . $supplier->id,
            'phone' => 'required|unique:suppliers,supplier_name,' . $supplier->id,
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->supplier_name = $request->supplier_name;
        $supplier->gstin = $request->gstin;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;
        $supplier->save();
        if ($supplier->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function destroy(supplier $supplier)
    {
        $productStock = Inventory::where('supplier_id', $supplier->id)->get()->count();

        if ($productStock == 0) {
            $supplier->forceDelete();
            return response()->json(['status' => 'success', 'message' => 'Supplier deleted successfully'], 200);
        }
        $supplier->delete();
        return response()->json(['status' => 'success', 'message' => 'Supplier soft-deleted successfully'], 200);
    }

    public function un_destroy($supplier)
    {
        $restored = Supplier::withTrashed()->find($supplier)->restore();
        if ($restored) {
            return response()->json(['status' => 'success', 'message' => 'Supplier Restored successfully'], 200);
        }
    }
}
