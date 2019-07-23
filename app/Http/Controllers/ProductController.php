<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Exports\StockExport;
use App\Http\Resources\Product as ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function getProductStock($branch_id)
    {
        if ($branch_id == 0) {
            $data = DB::table('products')
                ->where('inventory_product_details.sales_invoice', null)
                ->leftJoin('inventory_product_details', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('products.id', 'brands.brand_name', 'products.product_name', DB::raw("IFNULL(sum(inventory_product_details.imei_qty),0) as available_stock"))
                ->groupBy('products.id')
                ->get();
            return $data;

        } else if ($branch_id > 0) {
            $data = DB::table('products')
                ->where('inventory_product_details.sales_invoice', null)
                ->where('inventory_product_details.branch_id', $branch_id)
                ->leftJoin('inventory_product_details', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('products.id', 'brands.brand_name', 'products.product_name', DB::raw("IFNULL(sum(inventory_product_details.imei_qty),0) as available_stock"))
                ->groupBy('products.id')
                ->get();
            return $data;

            //            $grouped = $data->groupBy(function ($item, $key) {
            //                return $item->product_name;
            //            });
            //
            //            $groupCount = $grouped->map(function ($item, $key) {
            //                return collect($item)->count();
            //            });

            return $data;
        }
    }

    public function getProductStockExcel($branch_id)
    {
        if ($branch_id == 0) {
            $data = DB::table('products')
                ->where('inventory_product_details.sales_invoice', null)
                ->leftJoin('inventory_product_details', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('products.id', 'brands.brand_name', 'products.product_name', DB::raw("IFNULL(sum(inventory_product_details.imei_qty),0) as available_stock"))
                ->groupBy('products.id')
                ->get()->toArray();

            return Excel::download(new StockExport($data, [['Products Stock Report - All Branch'], [date('d-M-Y')], [],
                ['Product Name', 'Available Stock']]), 'stock.xlsx');

        } else if ($branch_id > 0) {
            $data = DB::table('products')
                ->where('inventory_product_details.sales_invoice', null)
                ->where('inventory_product_details.branch_id', $branch_id)
                ->leftJoin('inventory_product_details', function ($join) {
                    $join->on('products.id', '=', 'inventory_product_details.product_id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('products.id', 'brands.brand_name', 'products.product_name', DB::raw("IFNULL(sum(inventory_product_details.imei_qty),0) as available_stock"))
                ->groupBy('products.id')
                ->get()->toArray();

            $branch = Branch::find($branch_id)->first();

            return Excel::download(new StockExport($data, [['Products Stock Report - ' . $branch->branch_name . ' ' .
                $branch->branch_location], [date('d-M-Y')], [], ['Product Name', 'Available Stock']]), 'stock.xlsx');
        }
    }

    public function getProductsByBrand($brand_id)
    {
        $products = Product::where('brand_id', $brand_id)->get();
        return ProductResource::collection($products);
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
            'brand_id' => 'required',
            'product_name' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = new Product();
        $product->brand_id = $request->brand_id;
        $product->product_name = $request->product_name;
        $product->save();

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required',
            'product_name' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product->brand_id = $request->brand_id;
        $product->product_name = $request->product_name;
        $product->save();

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        //
    }
}
