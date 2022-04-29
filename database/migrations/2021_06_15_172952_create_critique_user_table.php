<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiqueUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critique_user', function (Blueprint $table) {
            $table->unsignedInteger('critique_id');
            $table->unsignedInteger('user_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('critique_id')->references('id')->on('critiques')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['critique_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critique_user');
    }
}
