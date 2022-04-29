<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * The serviceTypes that belong to the service.
     */
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class,'service_type_service');
    }

    public function packages() {

        return $this->belongsToMany(Package::class,'service_package');
            
    }

    public function orders() {

        return $this->belongsToMany(Order::class,'order_service');
            
    }
}
