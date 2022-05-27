<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'date',
        'status',
        'latitude',
        'longitude',
        'cancel_reason',
        'driver_id',
        'confirmed_date',
        'cancelled_date',
        'delivered_date',
        'location_str',
        'delivery_fee',
        'coupon_id'
    ];

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }

    public function orderDetails() {

    	return $this->hasMany('App\Models\OrderDetail');
    }

    public function coupon() {

        return $this->belongsTo('App\Models\Coupon');
    }
}
