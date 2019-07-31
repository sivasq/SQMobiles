<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Brand;
use App\Exports\ProductExport;
use App\Http\Resources\Product as ProductResource;
use App\InventoryProductDetail;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withTrashed()->get();
        return ProductResource::collection($products);
    }

    public function getProductsExcel()
    {
        $data = DB::table('products')
            ->join('brands', function ($join) {
                $join->on('products.brand_id', '=', 'brands.id');
            })
            ->select('products.id', 'brands.brand_name', 'products.product_name')
            ->get()->toArray();

        return Excel::download(new ProductExport($data, [['Products List'], [date('d-M-Y')], [],
            ['Brand Name', 'Product Name']]), 'productslist.xlsx');
    }

    public function getProductStock($branch_id, $brand_id)
    {
        if ($branch_id == 0) {
            $data = DB::table('products')
                ->when($brand_id, function ($query) use ($brand_id) {
                    return $query->where('products.brand_id', $brand_id);
                })
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
                ->when($brand_id, function ($query) use ($brand_id) {
                    return $query->where('products.brand_id', $brand_id);
                })
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

    public function getProductStockExcel($branch_id, $brand_id)
    {
        if ($branch_id == 0) {
            $data = DB::table('products')
                ->when($brand_id, function ($query) use ($brand_id) {
                    return $query->where('products.brand_id', $brand_id);
                })
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

            $brandName = "All Products";
            if ($brand_id > 0) {
                $brand = Brand::where('id', 2)->first();
                $brandName = $brand->brand_name;
            }
            return Excel::download(new StockExport($data, [[$brandName . ' Stock Report - All Branch'], [date('d-M-Y')], [],
                ['Product Name', 'Available Stock']]), 'stock.xlsx');

        } else if ($branch_id > 0) {
            $data = DB::table('products')
                ->when($brand_id, function ($query) use ($brand_id) {
                    return $query->where('products.brand_id', $brand_id);
                })
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

            $branch = Branch::where('id', $branch_id)->first();
            $brandName = "All Products";
            if ($brand_id > 0) {
                $brand = Brand::where('id', $brand_id)->first();
                $brandName = $brand->brand_name;
            }

            return Excel::download(new StockExport($data, [[$brandName . ' Stock Report - ' . $branch->branch_name
                . ' ' .
                $branch->branch_location], [date('d-M-Y')], [], ['Product Name', 'Available Stock']]), 'stock.xlsx');
        }
    }

    public function getProductsByBrand($brand_id)
    {
        $products = Product::where('brand_id', $brand_id)->get();
        return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required',
            'product_name' => 'required|unique:products,product_name,NULL,id,brand_id,' . Input::get('brand_id')
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
        if ($product->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function update(Request $request, product $product)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required',
            'product_name' => 'required|unique:products,product_name,' . $product->id . ',id,brand_id,' . Input::get
                ('brand_id')
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

        if ($product->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function destroy(product $product)
    {
        $productStock = InventoryProductDetail::where('product_id', $product->id)->get()->count();

        if ($productStock == 0) {
            $product->forceDelete();
            return response()->json(['status' => 'success', 'message' => 'Product deleted successfully'], 200);
        }
        $product->delete();
        return response()->json(['status' => 'success', 'message' => 'Product soft-deleted successfully'], 200);
    }

    public function un_destroy($branch)
    {
        $restored = Product::withTrashed()->find($branch)->restore();
        if ($restored) {
            return response()->json(['status' => 'success', 'message' => 'Product Restored successfully'], 200);
        }
    }
}
