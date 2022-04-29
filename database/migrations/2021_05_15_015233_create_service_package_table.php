<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_package', function (Blueprint $table) {
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('package_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['service_id','package_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_package');
    }
}
