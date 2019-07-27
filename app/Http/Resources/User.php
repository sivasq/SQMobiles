<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Branch;

class User extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'roles' => $this->roles,
            'activeStatus' => is_null($this->deleted_at) ? true : false,
            'branch_details' => $this->branch_details($this->branch_id)
        ];
    }

    public function branch_details($branch_id)
    {
        $branch_details = Branch::where('id', $branch_id)->withTrashed()->first();
        return new \App\Http\Resources\Branch($branch_details);
    }
}
