<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\MobileApi\BaseController as BaseController;
use App\Http\Resources\InventoryProductDetail as InventoryProductDetailResource;
use App\InventoryProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;


class InventoryController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getImeiBasedStockDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->get());
        } else if ($branch_id > 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', null)->where('branch_id', $branch_id)->get());
        }

        // return InventoryProductDetailResource::collection(InventoryProductDetail::where('branch_id', auth()->user()->branch_id)->get());
    }

    public function getImeiBasedSalesDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->get());
        } else if ($branch_id > 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->where('branch_id', $branch_id)->get());
        }
    }

    public function getProductDetailByImei(Request $request)
    {
        $branch_id = Auth::user()->branch_id;

        $data = DB::table('inventory_product_details')
            ->where('inventory_product_details.imei_number', $request->get('imei'))
            ->where('inventory_product_details.sales_invoice', null)
            ->where('inventory_product_details.branch_id', $branch_id)
            ->leftJoin('products', function ($join) {
                $join->on('inventory_product_details.product_id', '=', 'products.id');
            })
            ->join('brands', function ($join) {
                $join->on('products.brand_id', '=', 'brands.id');
            })
            ->select('inventory_product_details.id as imei_id', 'brands.brand_name', 'products.product_name')
            ->get();

        if (count($data) > 0)
            return $this->sendResponse($data, 'Imei Details Retrieved Successfully.');
        else
            return $this->sendResponse($data, 'No Stock or Already Sold This IMEI Number');
    }

    public function transferStock(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        $transferred = DB::table('inventory_product_details')
            ->where('id', $request->get('imei_id'))
            ->where('branch_id', $branch_id)
            ->update(['branch_id' => $request->get('transfer_to')]);
        if ($transferred) {
            return $this->sendResponse('', 'Stock Transferred Successfully.');
        } else {
            return $this->sendResponse('', 'No Stock or Already Sold This IMEI Number.');
        }
    }

    public function salesEntry(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        $sales = DB::table('inventory_product_details')
            ->where('id', $request->get('imei_id'))
            ->where('inventory_product_details.sales_invoice', null)
            ->where('branch_id', $branch_id)
            ->update(['sales_invoice' => $request->get('invoice_number'), 'sales_at' => now(), 'sales_by' =>
                Auth::user()->id]);
        if ($sales) {
            return $this->sendResponse('', 'Sales Completed Successfully.');
        } else {
            return $this->sendResponse('', 'No Stock or Already Sold This IMEI Number.');
        }
    }
}
