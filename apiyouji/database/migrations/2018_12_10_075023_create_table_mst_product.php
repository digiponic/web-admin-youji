<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMstProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_product', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name');
            $table->text('desc');
            $table->integer('category_id');
            $table->integer('unit_id');
            $table->double('price',8,2);
            $table->double('discount',8,2);
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
        Schema::dropIfExists('table_mst_product');
    }
}
