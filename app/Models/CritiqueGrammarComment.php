<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritiqueGrammarComment extends Model
{
    use HasFactory;

    public function critiques() {

        return $this->belongsToMany(Critique::class,'critique_grammar_comment_critique');
            
    }
}
