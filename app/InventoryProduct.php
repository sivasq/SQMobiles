<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryProduct extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
}
