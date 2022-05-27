<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use SoftDeletes;

    protected $date = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'fname',
        'lname',
        'address',
        'email',
        'password',
        'vkey',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() {

        return $this->belongsToMany('App\Models\Role');
    }

    public function company() {

        return $this->hasOne('App\Models\Company');
    }

    public function carts() {

        return $this->hasMany('App\Models\Carts');
    }

    public function order() {

        return $this->hasOne('App\Models\Order');
    }

    public function driver() {

        return $this->hasOne('App\Models\Driver');
    }

    public function ratings() {

        return $this->hasMany('App\Models\Rating');
    }

    public function favorites() {

        return $this->hasMany('App\Models\Favorite');
    }

    public function notifications() {

        return $this->hasMany('App\Models\Notification');
    }
}
