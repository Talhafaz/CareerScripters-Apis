<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTypeServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_type_service', function (Blueprint $table) {
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('service_type_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['service_id','service_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_type_service');
    }
}
