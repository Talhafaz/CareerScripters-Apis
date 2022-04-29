<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function users() {

        return $this->belongsToMany(User::class,'order_user');
            
    }

    public function services() {

        return $this->belongsToMany(Service::class,'order_service');
            
    }

    public function packages() {

        return $this->belongsToMany(Package::class,'order_package');
            
    }

    public function serviceTypes()
    {
        return $this->belongsToMany(ServiceType::class,'order_service_type');
    }
}
