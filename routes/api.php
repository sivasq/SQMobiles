<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Create New User
        Route::post('register', 'AuthController@register');

        // Login User
        Route::post('login', 'AuthController@login');

        // Refresh the JWT Token
        Route::get('refresh', 'AuthController@refresh');

        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', 'AuthController@user');
            // Logout user from application
            Route::post('logout', 'AuthController@logout');


            // Add New User
            Route::post('addUser', 'AuthController@register');
            // Get All Users
            Route::get('fetchUsers', 'AuthController@index');


            // Add Supplier
            Route::post('addSupplier', 'SupplierController@store');
            // Get All Suppliers
            Route::get('fetchSuppliers', 'SupplierController@index');


            // Add Brand
            Route::post('addBrand', 'BrandController@store');
            // Get All Brands
            Route::get('fetchBrands', 'BrandController@index');


            // Add Branch
            Route::post('addBranch', 'BranchController@store');
            // Get All Branches
            Route::get('fetchBranches', 'BranchController@index');


            // Add Product
            Route::post('addProduct', 'ProductController@store');
            // Get All Products
            Route::get('fetchProducts', 'ProductController@index');
            // Get Products BY Brand Id
            Route::get('fetchProductsByBrand/{brand_id}', 'ProductController@getProductsByBrand');


            // Add Inventory
            Route::post('addInventory', 'InventoryController@store');

            Route::get('getImeiBasedStockDetails', 'InventoryController@getImeiBasedStockDetails');

            Route::get('getImeiBasedSalesDetails', 'InventoryController@getImeiBasedSalesDetails');
        });
    });
});
