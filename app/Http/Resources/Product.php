<?php

namespace App\Http\Resources;

use App\Brand;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'brand_details' => $this->brand_details($this->brand_id),
            'stock' => $this->product_stock($this->id),
            'activeStatus' => is_null($this->deleted_at) ? true : false,
        ];
    }

    private function brand_details($brand_id)
    {
        $brand_details = Brand::where('id', $brand_id)->withTrashed()->first();
        return new \App\Http\Resources\Brand($brand_details);
    }

    private function product_stock($product_id)
    {
        //        return \App\InventoryProductDetail::where('sales_invoice', null)->where('product_id', $product_id)->get();

        return DB::table('inventory_product_details')
            ->where('inventory_product_details.sales_invoice', null)
            ->where('inventory_product_details.product_id', $product_id)
            ->where('inventory_product_details.branch_id', 2)
            ->join('branches', function ($join) {
                $join->on('inventory_product_details.branch_id', '=', 'branches.id');
            })
//            ->select('inventory_product_details.branch_id', 'branches.branch_name', 'branches.branch_location', DB::raw("count(*) as stock"))
//            ->groupBy('inventory_product_details.branch_id')
            ->get();
    }

    private function get_related_ids($product_id)
    {
        return $inventory_product_ids = \App\InventoryProduct::where('product_id', $product_id)->pluck('id');
    }

}
