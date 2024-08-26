<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        "lessor_id",
        "category_id",
        "name",
        "description",
        "location",
        "address",
        "price_per_hour",
        "availability",
        "capacity",
    ];

    protected $table = 'properties';

    public function bookings(){
        return $this->hasMany(Booking::class,'property_id');
    }

    public function lessor(){
        return $this->belongsTo(Lessor::class,'lessor_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function property_images(){
        return $this->hasMany(Property_image::class,'property_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}
