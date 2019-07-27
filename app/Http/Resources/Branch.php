<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Branch extends JsonResource
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
            'id' => $this->id,
            'branch_name' => $this->branch_name,
            'branch_location' => $this->branch_location,
            'activeStatus' => is_null($this->deleted_at) ? true : false,
        ];
    }
}
