<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Product;
use App\Inventory;

class InventoryProduct extends JsonResource
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
            'product_details' => $this->product_details($this->product_id),
            'inventory_detail' => $this->inventory_details($this->inventory_id)
        ];
    }

    private function product_details($product_id)
    {
        $product_details = Product::where('id', $product_id)->first();
        return new \App\Http\Resources\Product($product_details);
    }

    private function inventory_details($inventory_id)
    {
        $inventory_details = Inventory::where('id', $inventory_id)->first();
        return new \App\Http\Resources\Inventory($inventory_details);
    }
}
