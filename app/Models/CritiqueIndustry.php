<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritiqueIndustry extends Model
{
    use HasFactory;

    public function critiques() {

        return $this->belongsToMany(Critique::class,'critique_industry_critique');
            
    }
}
