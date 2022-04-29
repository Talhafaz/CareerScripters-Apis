<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critiques', function (Blueprint $table) {
            $table->id();
            $table->string("file");
            $table->integer("brevity")->nullable();
            $table->longText("brevity_description")->nullable();
            $table->integer("impact")->nullable();
            $table->longText("impact_description")->nullable();
            $table->integer("depth")->nullable();
            $table->longText("depth_description")->nullable();
            $table->integer("pages")->nullable();
            $table->longText("pages_description")->nullable();
            $table->integer("word_count")->nullable();
            $table->longText("word_count_description")->nullable();
            $table->string("file_size")->nullable();
            $table->longText("file_size_description")->nullable();
            $table->string("mail")->nullable();
            $table->string("phone")->nullable();
            $table->string("linkedin")->nullable();
            $table->string("address")->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critiques');
    }
}
