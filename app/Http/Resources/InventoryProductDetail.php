<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Branch;
use App\InventoryProduct;

class InventoryProductDetail extends JsonResource
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
            'imei_number' => $this->imei_number,
            'branch_detail' => $this->branch_details($this->branch_id),
            'inventory_product_detail' => $this->inventory_product_details($this->inventory_product_id)
        ];
    }

    private function branch_details($branch_id)
    {
        $branch_details = Branch::where('id', $branch_id)->first();
        return new \App\Http\Resources\Branch($branch_details);
    }

    private function inventory_product_details($inventory_product_id)
    {
        $inventory_product_details = InventoryProduct::where('id', $inventory_product_id)->first();
        return new \App\Http\Resources\InventoryProduct($inventory_product_details);
    }
}
