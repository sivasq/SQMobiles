<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->integer('imei_number', 11);
            $table->string('product_color', 50)->nullable();
            $table->string('unit_price', 12)->nullable();
            $table->string('cgst_percentage', 6)->nullable();
            $table->string('sgst_percentage', 6)->nullable();
            $table->string('gst_percentage', 6)->nullable();
            $table->string('cgst', 12)->nullable();
            $table->string('sgst', 12)->nullable();
            $table->string('gst', 12)->nullable();
            $table->string('total_price', 12)->nullable();
            $table->integer('branch_id');
            $table->timestamp('received_at')->nullable();
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
