<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\ImeiStockExport;
use App\Http\Resources\InventoryProductDetail as InventoryProductDetailResource;
use App\Inventory;
use App\InventoryProduct;
use App\InventoryProductDetail;
use App\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;


class InventoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'supplier_id' => 'required',
            'brand_id' => 'required',
            'product_id' => 'required',
            'product_qty' => 'required',
            'purchase_price' => 'required',
            'product_serial_numbers.*.imei_number' => 'required|unique:inventory_product_details',
        ],
            [
                'product_serial_numbers.*.imei_number.required' => 'Imei Number Required',
                'product_serial_numbers.*.imei_number.unique' => 'This IMEI Number Already taken'
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
                        'branch_id' => 1,
                        'product_id' => $request->product_id,
                        'received_at' => now(),
                    ];
                }
            }
            InventoryProductDetail::insert($inventory_imei_records);
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false'], 400);
        }
    }

    public function getImeiBasedStockDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->get());
        } else if ($branch_id > 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->where('branch_id', $branch_id)->get());
        }

        // return InventoryProductDetailResource::collection(InventoryProductDetail::where('branch_id', auth()->user()->branch_id)->get());
    }

    public function getImeiBasedStockDetailsByBranchExcel($branch_id)
    {
        if ($branch_id == 0) {
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.sales_invoice', null)
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->join('branches', function ($join) {
                    $join->on('inventory_product_details.branch_id', '=', 'branches.id');
                })
                ->get()->toArray();
            return Excel::download(new ImeiStockExport($data, [['Products Stock Report - All Branch'], [date('d-M-Y')], [],
                ['Product IMEI', 'Product Name', 'Branch', 'Location']]), 'stock.xlsx');

        } else if ($branch_id > 0) {
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.branch_id', $branch_id)
                ->where('inventory_product_details.sales_invoice', null)
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->join('branches', function ($join) {
                    $join->on('inventory_product_details.branch_id', '=', 'branches.id');
                })
                ->get()->toArray();

            $branch = Branch::find($branch_id)->first();

            return Excel::download(new ImeiStockExport($data, [['Products Stock Report - ' . $branch->branch_name . ' ' .
                $branch->branch_location], [date('d-M-Y')], [],
                ['Product IMEI', 'Product Name', 'Branch', 'Location']]), 'stock.xlsx');
        }
    }

    public function getImeiBasedSalesDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->get());
        } else if ($branch_id > 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->where('branch_id', $branch_id)->get());
        }
    }

    public function getProductStock($branch_id)
    {
        $products = Product::find(1);
        dd($products);
    }

    public function transferStock(Request $request)
    {
        $transfered = DB::table('inventory_product_details')->whereIn('id', $request->get('transfer_items'))->update
        (['branch_id' => $request->get('transfer_to')]);

        if ($transfered) {
            return response()->json(['success' => true], 200);
        }
    }
}
