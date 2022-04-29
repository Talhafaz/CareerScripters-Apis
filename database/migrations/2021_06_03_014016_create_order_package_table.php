<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_package', function (Blueprint $table) {
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('package_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['order_id','package_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_package');
    }
}
