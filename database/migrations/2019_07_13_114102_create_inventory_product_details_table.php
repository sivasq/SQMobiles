<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inventory_product_id');
            $table->foreign('inventory_product_id')->references('id')->on('inventory_products');
            $table->integer('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('imei_number');
            $table->integer('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->string('sales_invoice')->nullable();
            $table->timestamp('sales_at')->nullable();
            $table->integer('sale_by')->nullable();
            $table->foreign('sale_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_product_details');
    }
}
