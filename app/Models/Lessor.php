<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Codec\TimestampLastCombCodec;

class Lessor extends Model
{
    use HasFactory;

    protected $table = 'lessors';
    protected $fillable = ['name', 'email', 'password', 'phone_num', 'address'];


    public $timestamps = false;



    public function properties(){
        return $this->hasMany(Property::class,'lessor_id','id');
    }

}