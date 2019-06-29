<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstSalesOrderDetai extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_sales_order_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_order_id');
            $table->integer('product_id');
            $table->double('quantity',8,2);
            $table->double('price',8,2);
            $table->double('discount',8,2);
            $table->double('subtotal',8,2);
            $table->double('total',8,2);
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->string('deleted_user')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_sales_order_detail');
    }
}
