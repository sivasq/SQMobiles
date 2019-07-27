<?php

namespace App\Http\Resources;

use App\Branch;
use App\InventoryProduct;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

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
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'imei_number' => $this->imei_number,
            'sales_invoice' => $this->sales_invoice,
            'sales_at' => $this->sales_at,
            'branch_detail' => $this->branch_details($this->branch_id),
            'inventory_product_detail' => $this->inventory_product_details($this->inventory_product_id),
            'seller_detail' => $this->seller_details($this->sale_by)
        ];
    }

    private function branch_details($branch_id)
    {
        $branch_details = Branch::where('id', $branch_id)->withTrashed()->first();
        return new \App\Http\Resources\Branch($branch_details);
    }

    private function inventory_product_details($inventory_product_id)
    {
        $inventory_product_details = InventoryProduct::where('id', $inventory_product_id)->withTrashed()->first();
        return new \App\Http\Resources\InventoryProduct($inventory_product_details);
    }

    private function seller_details($user_id)
    {
        $user_details = User::where('id', $user_id)->withTrashed()->first();
        return new \App\Http\Resources\User($user_details);
    }
}
