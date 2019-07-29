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

//Event::listen('illuminate.query', function ($query) {
//    var_dump($query);
//});

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
            // Update User
            Route::post('updateUser/{user}', 'AuthController@updateUser');
            // Delete User
            Route::post('deleteUser/{user}', 'AuthController@destroy');
            // UnDelete User
            Route::post('unDeleteUser/{user}', 'AuthController@un_destroy');
            // Get All Users
            Route::get('fetchUsers', 'AuthController@index');


            // Add Supplier
            Route::post('addSupplier', 'SupplierController@store');
            // Update Supplier
            Route::post('updateSupplier/{supplier}', 'SupplierController@update');
            // Delete Supplier
            Route::post('deleteSupplier/{supplier}', 'SupplierController@destroy');
            // UnDelete Supplier
            Route::post('unDeleteSupplier/{supplier}', 'SupplierController@un_destroy');
            // Get All Suppliers
            Route::get('fetchSuppliers', 'SupplierController@index');


            // Add Brand
            Route::post('addBrand', 'BrandController@store');
            // Update Brand
            Route::post('updateBrand/{brand}', 'BrandController@update');
            // Delete Brand
            Route::post('deleteBrand/{brand}', 'BrandController@destroy');
            // UnDelete Brand
            Route::post('unDeleteBrand/{brand}', 'BrandController@un_destroy');
            // Get All Brands
            Route::get('fetchBrands', 'BrandController@index');


            // Add Branch
            Route::post('addBranch', 'BranchController@store');
            // Update Branch
            Route::post('updateBranch/{branch}', 'BranchController@update');
            // Delete Branch
            Route::post('deleteBranch/{branch}', 'BranchController@destroy');
            // UnDelete Branch
            Route::post('unDeleteBranch/{branch}', 'BranchController@un_destroy');
            // Get All Branches
            Route::get('fetchBranches', 'BranchController@index');


            // Add Product
            Route::post('addProduct', 'ProductController@store');
            // Update Product
            Route::post('updateProduct/{product}', 'ProductController@update');
            // Delete Product
            Route::post('deleteProduct/{product}', 'ProductController@destroy');
            // UnDelete Product
            Route::post('unDeleteProduct/{product}', 'ProductController@un_destroy');
            // Get All Products
            Route::get('fetchProducts', 'ProductController@index');
            // Get Products BY Brand Id
            Route::get('fetchProductsByBrand/{brand_id}', 'ProductController@getProductsByBrand');

            // Add Inventory
            Route::post('addInventory', 'InventoryController@store');

            Route::get('getImeiBasedStockDetails/{branch_id}', 'InventoryController@getImeiBasedStockDetailsByBranch');
            Route::get('getImeiBasedStockDetailsExcel/{branch_id}', 'InventoryController@getImeiBasedStockDetailsByBranchExcel');

            Route::get('getImeiBasedSalesDetails/{branch_id}', 'InventoryController@getImeiBasedSalesDetailsByBranch');

            Route::get('getProductStock/{branch_id}', 'ProductController@getProductStock');
            Route::get('getProductStockExcel/{branch_id}', 'ProductController@getProductStockExcel');

            Route::post('transferStock', 'InventoryController@transferStock');
        });
    });
});

Route::prefix('mobile')->group(function () {
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Login User
        Route::post('login', 'MobileApi\Auth\LoginController@login');

        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:mobileapi')->group(function () {

            Route::post('addStock', 'MobileApi\InventoryController@addStock');

            // Get user info
            Route::get('user', 'MobileApi\Auth\LoginController@details');

            // Logout user from application
            Route::post('logout', 'MobileApi\Auth\LoginController@logout');

            // Get Product Stock
            Route::get('get_stock', 'MobileApi\ProductController@getProductStock');

            // Get Product Details By IMEI
            Route::post('getProductDetailByImei', 'MobileApi\InventoryController@getProductDetailByImei');

            // Transfer Stock
            Route::post('transferStock', 'MobileApi\InventoryController@transferStock');

            // Make Sales Entry
            Route::post('salesEntry', 'MobileApi\InventoryController@salesEntry');

            // Get Branches
            Route::get('getBranches', 'MobileApi\BranchController@index');

            // Get Brands
            Route::get('getBrands', 'MobileApi\BrandController@index');
        });
    });
});
