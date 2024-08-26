<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

        protected $fillable = [
        'property_id',
        'user_id',
        'rating',
        'comment',

    ];

    protected $table = 'reviews';

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function property(){
        return $this->belongsTo(Property::class,'property_id','id');
    }
}
