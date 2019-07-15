<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Brand;

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
            'product_name' => $this->product_name,
            'brand_details' => $this->brand_details($this->brand_id),
        ];
    }

    private function brand_details($brand_id)
    {
        $brand_details = Brand::where('id', $brand_id)->first();
        return new \App\Http\Resources\Brand($brand_details);
    }
}
