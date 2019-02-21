<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMstPromotions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name');
            $table->text('desc');
            $table->double('discount',8,2);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
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
        Schema::dropIfExists('mst_promotions');
    }
}
