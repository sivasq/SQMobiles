<?php

namespace App\Http\Controllers\MobileApi;

use App\Brand;

class BrandController extends BaseController
{
    public function index()
    {
        $brands = Brand::select('id', 'brand_name')->get();
        return $this->sendResponse($brands, 'Brands Retrieved Successfully.');
    }
}
