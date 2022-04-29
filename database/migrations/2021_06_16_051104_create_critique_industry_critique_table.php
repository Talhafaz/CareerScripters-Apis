<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiqueIndustryCritiqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critique_industry_critique', function (Blueprint $table) {
            $table->unsignedInteger('critique_id');
            $table->unsignedInteger('industry_id'); 

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('critique_id')->references('id')->on('critiques')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('critique_industries')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['critique_id','industry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critique_industry_critique');
    }
}
