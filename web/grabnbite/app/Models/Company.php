<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image',
        'name',
        'contact',
        'description',
        'location',
        'latitude',
        'longitude',
        'business_permit',
        'dti_cert',
        'dti_form',
        'valid_id'
    ];

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }

    public function products() {

        return $this->hasMany('App\Models\Product');
    }

    public function ratings() {

        return $this->hasMany('App\Models\Rating');
    }

    public function coupons() {

        return $this->hasMany('App\Models\Coupon');
    }
}
