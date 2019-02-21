<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstSalesOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_sales_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->integer('customer_id');
            $table->integer('customer_address_id');
            $table->datetime('datetime_shipping');
            $table->datetime('datetime_order');
            $table->string('note');
            $table->double('postal_fee',8,2);
            $table->double('discount',8,2);
            $table->double('sub_total',8,2);
            $table->double('total',8,2);
            $table->string('status');
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
        Schema::dropIfExists('mst_sales_orders');
    }
}
