<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTypePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_type_package', function (Blueprint $table) {
            $table->unsignedInteger('package_id');
            $table->unsignedInteger('service_type_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('service_types')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['package_id','service_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_type_package');
    }
}
