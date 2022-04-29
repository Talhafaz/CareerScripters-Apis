<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_user', function (Blueprint $table) {
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('user_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['order_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_user');
    }
}
