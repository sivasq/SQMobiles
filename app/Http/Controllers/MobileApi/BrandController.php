<?php

namespace App\Http\Controllers\MobileApi;

use App\Brand;

class BrandController extends BaseController
{
    public function index()
    {
        $brands = Brand::select('id', 'brand_name')->get();
        $parsedData = collect($brands);
        $parsedData[0] = ['id' => '0', 'brand_name' => 'Select Brand'];
        return $this->sendResponse($parsedData, 'Brands Retrieved Successfully.');
    }
}
