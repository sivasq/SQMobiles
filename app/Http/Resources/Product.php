<?php

namespace App\Http\Resources;

use App\Brand;
use Illuminate\Http\Resources\Json\JsonResource;

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
        //        return parent::toArray($request);
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'brand_details' => $this->brand_details($this->brand_id),
            'stock' => $this->product_stock($this->id),
        ];
    }

    private function brand_details($brand_id)
    {
        $brand_details = Brand::where('id', $brand_id)->first();
        return new \App\Http\Resources\Brand($brand_details);
    }

    private function get_related_ids($product_id)
    {
        return $inventory_product_ids = \App\InventoryProduct::where('product_id', $product_id)->pluck('id');
    }

    private function product_stock($product_id)
    {
        $inventory_product_ids = $this->get_related_ids($product_id);

        $stocks = \App\InventoryProductDetail::where('sales_invoice', null)->whereIn('inventory_product_id', $inventory_product_ids)->get();

        return $stocks;
    }

}
