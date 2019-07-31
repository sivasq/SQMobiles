<?php

namespace App\Http\Controllers\MobileApi;

use App\Brand;

class BrandController extends BaseController
{
    public function index()
    {
        $brands = Brand::select('id', 'brand_name')->get();
        $parsedData = collect($brands)->toArray();
        array_unshift($parsedData, ['id' => '0', 'brand_name' => 'Select Brand']);
        return $this->sendResponse($parsedData, 'Brands Retrieved Successfully.');
    }
}
