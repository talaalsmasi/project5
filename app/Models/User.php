<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'Photo',
        'role_id',
    ];

    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function reviews(){
        return $this->hasMany(Review::class, 'user_id', 'id');
    }

    public function lessor(){
        return $this->hasOne(Lessor::class, 'user_id', 'id');
    }

    public function bookings(){
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    /**
     * Set default role_id if not provided.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (is_null($user->role_id)) {
                $user->role_id = 1; // Default role_id
            }
        });
    }
}