<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiqueGrammarCommentCritiqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('critique_grammar_comment_critique', function (Blueprint $table) {
            $table->unsignedInteger('critique_id');
            $table->unsignedInteger('gc_id');

         //FOREIGN KEY CONSTRAINTS
            $table->foreign('critique_id')->references('id')->on('critiques')->onDelete('cascade');
            $table->foreign('gc_id')->references('id')->on('critique_grammar_comments')->onDelete('cascade');

         //SETTING THE PRIMARY KEYS
            $table->primary(['critique_id','gc_id']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('critique_grammar_comment_critique');
    }
}
