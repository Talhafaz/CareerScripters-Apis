<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    public function services() {

        return $this->belongsToMany(Service::class,'service_type_service');
            
    }

    public function packages() {

        return $this->belongsToMany(Package::class,'service_type_package');
            
    }
}
