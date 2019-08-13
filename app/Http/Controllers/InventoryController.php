<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\ImeiStockExport;
use App\Inventory;
use App\InventoryProduct;
use App\InventoryProductDetail;
use App\InventoryProductDetailTxnHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class InventoryController extends Controller
{
    public function extraInventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'supplier_id' => 'required',
            'products_details_list.*.brand_id' => 'required',
            'products_details_list.*.product_id' => 'required',
            'products_details_list.*.product_color' => 'required',
            'products_details_list.*.imei_number' => 'required|unique:inventory_product_details',
            'products_details_list.*.unit_price' => 'required',
            'products_details_list.*.gst' => 'required',
            'products_details_list.*.total_price' => 'required',
        ],
            [
                'products_details_list.*.brand_id.required' => 'Brand Required',
                'products_details_list.*.product_id.required' => 'Product Required',
                'products_details_list.*.imei_number.required' => 'Imei Number Required',
                'products_details_list.*.imei_number.unique' => 'This IMEI Number Already taken',
                'products_details_list.*.product_color.required' => 'Color Required',
                'products_details_list.*.unit_price.required' => 'Unit Price Required',
                'products_details_list.*.gst.required' => 'GST Required',
                'products_details_list.*.total_price.required' => 'Total Price Required',
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

            $products_details_list = collect($request->get('products_details_list'))->map(function ($item) {
                unset($item['products_in_brand']);
                return $item;
            })->groupBy('product_id')->toArray();

            foreach ($products_details_list as $key => $value) {
                $inventoryProduct = new InventoryProduct();
                $inventoryProduct->inventory_id = $inventory->id;
                $inventoryProduct->product_id = $value[0]['product_id'];
                $inventoryProduct->inventory_product_qty = count($value);
                $inventoryProduct->purchase_price_per_qty = 0;
                $inventoryProduct->save();

                $filtered_imei_numbers = collect($value)->filter(function ($item) {
                    return $item['imei_number'] != null;
                })->values()->toArray();

                // Inventory_imei_records to be saved
                $inventory_imei_records = [];
                foreach ($filtered_imei_numbers as $products_details) {
                    if (!empty($products_details['imei_number'])) {
                        $inventory_imei_records[] = [
                            'inventory_product_id' => $inventoryProduct->id,
                            'imei_number' => $products_details['imei_number'],
                            'branch_id' => 1,
                            'product_id' => $products_details['product_id'],
                            'product_color' => $products_details['product_color'],
                            'unit_price' => $products_details['unit_price'],
                            'gst' => $products_details['gst'],
                            'total_price' => $products_details['total_price'],
                            'received_from' => null,
                            'received_at' => null,
                            'created_at' => now(),
                        ];
                    }
                }

                $imeiEntry = InventoryProductDetail::insert($inventory_imei_records);

                if ($imeiEntry) {
                    $imeiEntryIds = InventoryProductDetail::orderBy('id', 'desc')->take(count($inventory_imei_records))
                        ->pluck('id')->toArray();

                    $auth_user = Auth::user();
                    $user_id = $auth_user->id;
                    $user_name = $auth_user->name;
                    $txn_histories = [];
                    $imeiEntryIds = array_reverse($imeiEntryIds);
                    foreach ($imeiEntryIds as $imeiEntryId) {
                        $txn_histories[] = [
                            'inventory_product_detail_id' => $imeiEntryId,
                            'txn_details' => "stock added to warehouse by " . $user_name,
                            'txn_by' => $user_id,
                            'created_at' => now()
                        ];
                    }
                    $imeiTxnLog = InventoryProductDetailTxnHistory::insert($txn_histories);
                }
            }
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false', 'msg' => $e->getMessage()], 400);
        }
    }

    public function addInventory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required',
            'supplier_id' => 'required',
            'brand_id' => 'required',
            'product_id' => 'required',
            'product_qty' => 'required',
            'products_details_list.*.imei_number' => 'required|unique:inventory_product_details',
            'products_details_list.*.product_color' => 'required',
            'products_details_list.*.unit_price' => 'required',
            'products_details_list.*.gst' => 'required',
            'products_details_list.*.total_price' => 'required',
        ],
            [
                'products_details_list.*.imei_number.required' => 'Imei Number Required',
                'products_details_list.*.imei_number.unique' => 'This IMEI Number Already taken',
                'products_details_list.*.product_color.required' => 'Color Required',
                'products_details_list.*.unit_price.required' => 'Unit Price Required',
                'products_details_list.*.gst.required' => 'GST Required',
                'products_details_list.*.total_price.required' => 'Total Price Required',

            ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        dd($request->all());

        try {
            DB::beginTransaction();

            $inventory = Inventory::firstOrCreate(
                ['invoice_number' => $request->invoice_number],
                ['supplier_id' => $request->supplier_id]
            );

            $filtered_imei_numbers = collect($request->products_details_list)->filter(function ($item) {
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
            foreach ($request->products_details_list as $products_detail) {
                if (!empty($products_detail['imei_number'])) {
                    $inventory_imei_records[] = [
                        'inventory_product_id' => $inventoryProduct->id,
                        'imei_number' => $products_detail['imei_number'],
                        'branch_id' => 1,
                        'product_id' => $request->product_id,
                        'received_from' => null,
                        'received_at' => null,
                        'created_at' => now(),
                    ];
                }
            }
            $imeiEntry = InventoryProductDetail::insert($inventory_imei_records);

            if ($imeiEntry) {
                $imeiEntryIds = InventoryProductDetail::orderBy('id', 'desc')->take(count($inventory_imei_records))
                    ->pluck('id')->toArray();

                $auth_user = Auth::user();
                $user_id = $auth_user->id;
                $user_name = $auth_user->name;
                $txn_histories = [];
                $imeiEntryIds = array_reverse($imeiEntryIds);
                foreach ($imeiEntryIds as $imeiEntryId) {
                    $txn_histories[] = [
                        'inventory_product_detail_id' => $imeiEntryId,
                        'txn_details' => "stock added to warehouse by " . $user_name,
                        'txn_by' => $user_id,
                        'created_at' => now()
                    ];
                }
                $imeiTxnLog = InventoryProductDetailTxnHistory::insert($txn_histories);
            }
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false', 'msg' => $e->getMessage()], 400);
        }
    }

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
                        'received_from' => null,
                        'received_at' => null,
                        'created_at' => now(),
                    ];
                }
            }
            $imeiEntry = InventoryProductDetail::insert($inventory_imei_records);

            if ($imeiEntry) {
                $imeiEntryIds = InventoryProductDetail::orderBy('id', 'desc')->take(count($inventory_imei_records))
                    ->pluck('id')->toArray();

                $auth_user = Auth::user();
                $user_id = $auth_user->id;
                $user_name = $auth_user->name;
                $txn_histories = [];
                $imeiEntryIds = array_reverse($imeiEntryIds);
                foreach ($imeiEntryIds as $imeiEntryId) {
                    $txn_histories[] = [
                        'inventory_product_detail_id' => $imeiEntryId,
                        'txn_details' => "stock added to warehouse by " . $user_name,
                        'txn_by' => $user_id,
                        'created_at' => now()
                    ];
                }
                $imeiTxnLog = InventoryProductDetailTxnHistory::insert($txn_histories);
            }
            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'false', 'msg' => $e->getMessage()], 400);
        }
    }

    public function getImeiBasedStockDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            //            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->get());
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
                ->join('inventory_products', function ($join) {
                    $join->on('inventory_product_details.inventory_product_id', '=', 'inventory_products.id');
                })
                ->join('inventories', function ($join) {
                    $join->on('inventory_products.inventory_id', '=', 'inventories.id');
                })
                ->join('suppliers', function ($join) {
                    $join->on('inventories.supplier_id', '=', 'suppliers.id');
                })
                ->select('inventory_product_details.*', 'suppliers.*', 'inventories.*', 'inventory_products.*', 'branches.*', 'brands.*', 'products.*',
                    'inventory_product_details.id as id')
                ->get()->toArray();

            return $data;
        } else if ($branch_id > 0) {
            //            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->where('branch_id', $branch_id)->get());
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
                ->join('inventory_products', function ($join) {
                    $join->on('inventory_product_details.inventory_product_id', '=', 'inventory_products.id');
                })
                ->join('inventories', function ($join) {
                    $join->on('inventory_products.inventory_id', '=', 'inventories.id');
                })
                ->join('suppliers', function ($join) {
                    $join->on('inventories.supplier_id', '=', 'suppliers.id');
                })
                ->select('inventory_product_details.*', 'suppliers.*', 'inventories.*', 'inventory_products.*', 'branches.*', 'brands.*', 'products.*',
                    'inventory_product_details.id as id')
                ->get()->toArray();

            return $data;
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

    public function getImeiBasedSalesDetailsByBranch($branch_id, Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        if ($branch_id == 0) {
            //            DB::enableQueryLog();
            //            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->get());
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.sales_invoice', '!=', null)
                ->whereBetween('inventory_product_details.sales_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->join('branches', function ($join) {
                    $join->on('inventory_product_details.branch_id', '=', 'branches.id');
                })
                ->join('inventory_products', function ($join) {
                    $join->on('inventory_product_details.inventory_product_id', '=', 'inventory_products.id');
                })
                ->join('inventories', function ($join) {
                    $join->on('inventory_products.inventory_id', '=', 'inventories.id');
                })
                ->join('suppliers', function ($join) {
                    $join->on('inventories.supplier_id', '=', 'suppliers.id');
                })
                ->join('users', function ($join) {
                    $join->on('inventory_product_details.sale_by', '=', 'users.id');
                })
                ->select('inventory_product_details.*', 'suppliers.*', 'inventories.*', 'inventory_products.*', 'branches.*', 'brands.*', 'products.*',
                    'inventory_product_details.id as id', 'users.name')
                ->get()->toArray();
            //            dd(DB::getQueryLog());
            return $data;
        } else if ($branch_id > 0) {
            //            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->where('branch_id', $branch_id)->get());
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.branch_id', $branch_id)
                ->where('inventory_product_details.sales_invoice', '!=', null)
                ->whereBetween('inventory_product_details.sales_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->join('branches', function ($join) {
                    $join->on('inventory_product_details.branch_id', '=', 'branches.id');
                })
                ->join('inventory_products', function ($join) {
                    $join->on('inventory_product_details.inventory_product_id', '=', 'inventory_products.id');
                })
                ->join('inventories', function ($join) {
                    $join->on('inventory_products.inventory_id', '=', 'inventories.id');
                })
                ->join('suppliers', function ($join) {
                    $join->on('inventories.supplier_id', '=', 'suppliers.id');
                })
                ->join('users', function ($join) {
                    $join->on('inventory_product_details.sale_by', '=', 'users.id');
                })
                ->select('inventory_product_details.*', 'suppliers.*', 'inventories.*', 'inventory_products.*', 'branches.*', 'brands.*', 'products.*',
                    'inventory_product_details.id as id', 'users.name')
                ->get()->toArray();

            return $data;
        }
    }

    public function transferStock(Request $request)
    {
        $transfered = DB::table('inventory_product_details')->whereIn('id', $request->get('transfer_items'))->update
        (['branch_id' => $request->get('transfer_to'), 'received_from' => $request->get('transfer_from'), 'received_at'
        => null, 'updated_at'
        => now()]);

        $from = Branch::select(DB::raw("CONCAT(branches.branch_name, '-', branches.branch_location) as branch"))->where
        ('id', $request->get('transfer_from'))->first();

        $to = Branch::select(DB::raw("CONCAT(branches.branch_name, '-', branches.branch_location) as branch"))->where
        ('id', $request->get('transfer_to'))->first();

        if ($transfered) {
            $auth_user = Auth::user();
            $user_id = $auth_user->id;
            $user_name = $auth_user->name;
            $txn_histories = [];
            $imeiEntryIds = $request->get('transfer_items');
            foreach ($imeiEntryIds as $imeiEntryId) {
                $txn_histories[] = [
                    'inventory_product_detail_id' => $imeiEntryId,
                    'txn_details' => "stock transferred to " . $to->branch . " from " . $from->branch . " by " . $user_name,
                    'txn_by' => $user_id,
                    'created_at' => now()
                ];
            }
            $imeiTxnLog = InventoryProductDetailTxnHistory::insert($txn_histories);

            return response()->json(['success' => true], 200);
        }
    }
}
