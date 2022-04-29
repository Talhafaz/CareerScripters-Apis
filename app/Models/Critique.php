<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critique extends Model
{
    use HasFactory;

    public function users() {

        return $this->belongsToMany(User::class,'critique_user');
            
    }

    public function critiqueGrammarComments() {

        return $this->belongsToMany(CritiqueGrammarComment::class,'critique_grammar_comment_critique','critique_id','gc_id');
            
    }

    public function critiquePresentationComments() {

        return $this->belongsToMany(CritiquePresentationComment::class,'critique_presentation_comment_critique','critique_id','pc_id');
            
    }

    public function critiqueIndustries() {

        return $this->belongsToMany(CritiqueIndustry::class,'critique_industry_critique','critique_id','industry_id');
            
    }
}
