<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Http\Resources\Brand as BrandResource;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return BrandResource::collection($brands);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|unique:brands',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $brand = new Brand();
        $brand->brand_name = $request->brand_name;
        $brand->save();

        if ($brand->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function update(Request $request, brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|unique:brands,brand_name,' . $brand->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $brand->brand_name = $request->brand_name;
        $brand->save();

        if ($brand->exists()) {
            return response()->json(['status' => 'success'], 200);
        }
    }

    public function destroy(brand $brand)
    {
        $productExists = Product::where('brand_id', $brand->id)->get()->count();

        if ($productExists == 0) {
            $brand::destroy($brand->id);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'false'], 200);
    }
}
