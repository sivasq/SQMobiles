<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryProductDetailTxnHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_product_detail_txn_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('inventory_product_detail_id');
            $table->foreign('inventory_product_detail_id', 'inv_prt_detail_id_foreign')->references('id')->on('inventory_product_details');
            $table->integer('txn_from');
            $table->foreign('txn_from')->references('id')->on('branches');
            $table->integer('txn_to');
            $table->foreign('txn_to')->references('id')->on('branches');
            $table->integer('txn_by')->nullable();
            $table->foreign('txn_by')->references('id')->on('users');
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
        Schema::dropIfExists('inventory_product_detail_txn_histories');
    }
}
