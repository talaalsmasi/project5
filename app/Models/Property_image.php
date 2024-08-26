<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property_image extends Model
{
    use HasFactory;

        protected $table = 'property_images';
    protected $fillable = [
        'image','property_id'
    ] ;

    public $timestamps = false;

    public function property(){
        return $this->belongsTo(Property::class,'property_id','id');
    }
}
