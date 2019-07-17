<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product as ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return ProductResource::collection($products);
    }

    public function getProductStock($branch_id)
    {
        //        if ($branch_id == 0) {
        //            return ProductResource::collection(Product::all());
        //        } else if ($branch_id > 0) {
        $data = DB::table('inventory_product_details')
            ->where('inventory_product_details.sales_invoice', null)
            ->join('inventory_products', function ($join) {
                $join->on('inventory_product_details.inventory_product_id', '=', 'inventory_products.id');
            })
            ->join('products', function ($join) {
                $join->on('inventory_products.product_id', '=', 'products.id');
            })
            ->get();

        $grouped = $data->groupBy(function ($item, $key) {
            return $item->product_name;
        });

//        $groupCount = $grouped->map(function ($item, $key) {
//            return collect($item)->count();
//        });

        return $grouped;
        //        }
    }

    public function getProductsByBrand($brand_id)
    {
        $products = Product::where('brand_id', $brand_id)->get();
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_id' => 'required',
            'product_name' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = new Product();
        $product->brand_id = $request->brand_id;
        $product->product_name = $request->product_name;
        $product->save();

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        //
    }
}
