<?php

namespace App\Http\Controllers\MobileApi;

use App\Http\Controllers\MobileApi\BaseController as BaseController;
use App\Http\Resources\InventoryProductDetail as InventoryProductDetailResource;
use App\InventoryProduct;
use App\InventoryProductDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class InventoryController extends BaseController
{
    public function addStock(Request $request)
    {
        if (Auth::user()->roles != 'stockuser') {
            return response()->json(['status' => 'false', 'message' => 'unAuthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'brand_id' => 'required',
            'product_id' => 'required',
            'branch_id' => 'required',
            'imei_number' => 'required|unique:inventory_product_details',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $inventoryProduct = InventoryProduct::updateOrCreate(
                ['inventory_id' => 2, 'product_id' => $request->product_id],
                ['inventory_product_qty' => \DB::raw('inventory_product_qty + 1'), 'purchase_price_per_qty' => 0]
            );

            // Inventory_imei_records to be saved
            $inventory_imei_records[] = [
                'inventory_product_id' => $inventoryProduct->id,
                'imei_number' => $request['imei_number'],
                'branch_id' => $request->branch_id,
                'product_id' => $request->product_id,
                'received_at' => now(),
            ];

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

    public function getImeiBasedSalesDetailsByBranch($branch_id)
    {
        if ($branch_id == 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->get());
        } else if ($branch_id > 0) {
            return InventoryProductDetailResource::collection(InventoryProductDetail::where('sales_invoice', '!=', null)->where('branch_id', $branch_id)->get());
        }
    }

    public function getProductDetailByImei_old(Request $request)
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

    public function getProductDetailByImei(Request $request)
    {
        $v = Validator::make($request->all(), [
            'imei' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'errorType' => "validation",
                'errors' => $v->errors()
            ], 422);
        }

        $user_branch_id = Auth::user()->branch_id;
        $Imei_product_details = InventoryProductDetail::where('imei_number', $request->get('imei'))->first();

        if (is_null($Imei_product_details)) {
            return $this->sendResponse([], 'This IMEI Number Not Available.');
        }

        if (!is_null($Imei_product_details->sales_at)) {
            return $this->sendError('This Product Already Sold');
        }

        if (($Imei_product_details->branch_id !== $user_branch_id))
        {
            return $this->sendError('You have No Stock');
        }

        if (($Imei_product_details->branch_id === $user_branch_id) && (is_null($Imei_product_details->received_at))) {
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.imei_number', $request->get('imei'))
                ->leftJoin('products', function ($join) {
                    $join->on('inventory_product_details.product_id', '=', 'products.id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('inventory_product_details.id as imei_id', 'brands.brand_name', 'products.product_name')
                ->get();
            return $this->sendResponse($data, 'You Have Stock in your Branch, But Not Received');
        }

        if (($Imei_product_details->branch_id === $user_branch_id) && (!is_null($Imei_product_details->received_at))) {
            $data = DB::table('inventory_product_details')
                ->where('inventory_product_details.imei_number', $request->get('imei'))
                ->leftJoin('products', function ($join) {
                    $join->on('inventory_product_details.product_id', '=', 'products.id');
                })
                ->join('brands', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id');
                })
                ->select('inventory_product_details.id as imei_id', 'brands.brand_name', 'products.product_name')
                ->get();
            return $this->sendResponse($data, 'You Have Stock in your Branch');
        }
        return $this->sendError('Unknown Error Occurred');
    }

    public function transferStock_old(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        $transferred = DB::table('inventory_product_details')
            ->where('id', $request->get('imei_id'))
            ->where('branch_id', $branch_id)
            // ->where('received_at', '!=', null)
            // ->update(['branch_id' => $request->get('transfer_to')]);
            // ->update(['branch_id' => $request->get('transfer_to'), 'received_at' => now()]);
            ->update(['branch_id' => $request->get('transfer_to'), 'received_at' => now()]);
        if ($transferred) {
            return $this->sendResponse('', 'Stock Transferred Successfully.');
        } else {
            return $this->sendResponse('', 'No Stock or Already Sold This IMEI Number.');
        }
    }

    public function transferStock(Request $request)
    {
        $v = Validator::make($request->all(), [
            'imei_id' => 'required',
            'transfer_to' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'errorType' => "validation",
                'errors' => $v->errors()
            ], 422);
        }

        $user_branch_id = Auth::user()->branch_id;
        $Imei_product_details = InventoryProductDetail::where('id', $request->get('imei_id'))->first();

        if (is_null($Imei_product_details)) {
            return $this->sendError('Invalid IMEI ID');
        }
        if ($Imei_product_details->branch_id !== $user_branch_id) {
            return $this->sendError('No Stock in your Branch');
        }
        if (is_null($Imei_product_details->received_at)) {
            return $this->sendError('Please Receive This Product');
        }
        if (!is_null($Imei_product_details->sales_at)) {
            return $this->sendError('This Product Already Sold');
        }

        $Imei_product_details->branch_id = $request->get('transfer_to');
        $Imei_product_details->received_at = null;
        $Imei_product_details->save();

        if ($Imei_product_details->getChanges()) {
            return $this->sendResponse([], 'Stock Transferred Successfully.');
        }
        return $this->sendError('Unknown Error Occurred');
    }

    public function salesEntry_old(Request $request)
    {
        $branch_id = Auth::user()->branch_id;
        $sales = DB::table('inventory_product_details')
            ->where('id', $request->get('imei_id'))
            ->where('inventory_product_details.sales_invoice', null)
            ->where('branch_id', $branch_id)
            // ->where('received_at', '!=', null)
            ->update(['sales_invoice' => $request->get('invoice_number'), 'sales_at' => now(), 'sale_by' =>
                Auth::user()->id]);
        if ($sales) {
            return $this->sendResponse('', 'Sales Completed Successfully.');
        } else {
            return $this->sendResponse('', 'No Stock or Already Sold This IMEI Number.');
        }
    }

    public function salesEntry(Request $request)
    {
        $v = Validator::make($request->all(), [
            'imei_id' => 'required',
            'invoice_number' => 'required'
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'errorType' => "validation",
                'errors' => $v->errors()
            ], 422);
        }

        $user_branch_id = Auth::user()->branch_id;
        $Imei_product_details = InventoryProductDetail::where('id', $request->get('imei_id'))->first();

        if (is_null($Imei_product_details)) {
            return $this->sendError('Invalid IMEI ID');
        }
        if ($Imei_product_details->branch_id !== $user_branch_id) {
            return $this->sendError('No Stock in your Branch');
        }
        if (is_null($Imei_product_details->received_at)) {
            return $this->sendError('Please Receive This Product');
        }
        if (!is_null($Imei_product_details->sales_at)) {
            return $this->sendError('This Product Already Sold');
        }

        $Imei_product_details->sales_invoice = $request->get('invoice_number');
        $Imei_product_details->sales_at = now();
        $Imei_product_details->sale_by = Auth::user()->id;
        $Imei_product_details->save();

        if ($Imei_product_details->getChanges()) {
            return $this->sendResponse([], 'Sales Completed Successfully.');
        }
        return $this->sendError('Unknown Error Occurred');
    }

    public function receiveStock(Request $request)
    {
        $v = Validator::make($request->all(), [
            'imei_id' => 'required',
        ]);

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'errorType' => "validation",
                'errors' => $v->errors()
            ], 422);
        }

        $user_branch_id = Auth::user()->branch_id;
        $Imei_product_details = InventoryProductDetail::where('id', $request->get('imei_id'))->first();

        if (is_null($Imei_product_details)) {
            return $this->sendError('Invalid IMEI ID');
        }
        if ($Imei_product_details->branch_id !== $user_branch_id) {
            return $this->sendError('No Stock in your Branch');
        }
        if (!is_null($Imei_product_details->sales_at)) {
            return $this->sendError('This Product Already Sold');
        }
        if (!is_null($Imei_product_details->received_at)) {
            return $this->sendError('This Product Already Received');
        }

        $Imei_product_details->received_at = now();
        $Imei_product_details->save();

        if ($Imei_product_details->getChanges()) {
            return $this->sendResponse([], 'Stock Received Successfully.');
        }
        return $this->sendError('Unknown Error Occurred');
    }
}
