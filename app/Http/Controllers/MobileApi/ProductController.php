<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\MobileApi\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends BaseController
{
    public function getProductsByBrand($brand_id)
    {
        $data = DB::table('products')
            ->where('brand_id', $brand_id)
            ->get();
        $parsedData = collect($data);

        $collection = $parsedData->map(function ($item) {
            return ['id' => $item->id, 'product_name' => $item->product_name];
        })->toArray();
        array_unshift($collection, ['id' => '0', 'product_name' => 'Select Product']);
        return $this->sendResponse($collection, 'Product Retrieved Successfully.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductStock()
    {
        $branch_id = Auth::user()->branch_id;
        $data = DB::table('products')
            ->where('inventory_product_details.sales_invoice', null)
            ->where('inventory_product_details.branch_id', $branch_id)
            ->leftJoin('inventory_product_details', function ($join) {
                $join->on('products.id', '=', 'inventory_product_details.product_id');
            })
            ->join('brands', function ($join) {
                $join->on('products.brand_id', '=', 'brands.id');
            })
            ->select('products.id', 'brands.brand_name', 'products.product_name', DB::raw("IFNULL(sum(inventory_product_details.imei_qty),0) as available_stock"), DB::raw("GROUP_CONCAT(imei_number) as imei_numbers"))
            ->groupBy('products.id')
            ->get();
        return $this->sendResponse($data, 'Stock Details Retrieved Successfully.');
    }

    public function getReceivingProductStock()
    {
        $branch_id = Auth::user()->branch_id;
        $data = DB::table('inventory_product_details')
            ->where('inventory_product_details.branch_id', $branch_id)
            ->where('inventory_product_details.received_at', null)
            ->leftJoin('products', function ($join) {
                $join->on('inventory_product_details.product_id', '=', 'products.id');
            })
            ->join('branches', function ($join) {
                $join->on('inventory_product_details.received_from', '=', 'branches.id');
            })
            ->join('brands', function ($join) {
                $join->on('products.brand_id', '=', 'brands.id');
            })
            ->select('inventory_product_details.imei_number', 'brands.brand_name', 'products.product_name',
                DB::raw("CONCAT(branches.branch_name, '-', branches.branch_location) as sent_from"))
            ->get();
        return $this->sendResponse($data, 'NotReceived Stock Details Retrieved Successfully.');
    }
}
