<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_service', function (Blueprint $table) {
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('service_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['order_id','service_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_service');
    }
}
