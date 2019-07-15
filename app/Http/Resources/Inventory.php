<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Supplier;

class Inventory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'invoice_number' => $this->invoice_number,
            'supplier_details' => $this->supplier_details($this->supplier_id),
        ];
    }

    private function supplier_details($supplier_id)
    {
        $supplier_details = Supplier::where('id', $supplier_id)->first();
        return new \App\Http\Resources\Supplier($supplier_details);
    }
}
