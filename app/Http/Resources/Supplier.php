<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Supplier extends JsonResource
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
            'supplier_name' => $this->supplier_name,
            'gstin' => $this->gstin,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'activeStatus' => is_null($this->deleted_at) ? true : false,
        ];
    }
}
