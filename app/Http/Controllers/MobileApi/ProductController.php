<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\MobileApi\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends BaseController
{
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
}
