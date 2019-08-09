<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class InventoryProductDetailTxnHistoryController extends Controller
{
    public function getImeiTxnLog($imei_id)
    {
        $data = DB::table('inventory_product_detail_txn_histories')
            ->where('inventory_product_detail_txn_histories.inventory_product_detail_id', $imei_id)
            ->get()->toArray();
        return $data;
    }
}
