<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    /**
     * The serviceTypes that belong to the package.
     */
    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class,'service_type_package');
    }

    public function services() {

        return $this->belongsToMany(Service::class,'service_package');
            
    }

    public function orders() {

        return $this->belongsToMany(Order::class,'order_package');
            
    }
}
