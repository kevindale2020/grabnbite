<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'image',
        'name',
        'description',
        'price',
        'qty',
        'addon_id',
        'category_id'
    ];

    public function company() {

    	return $this->belongsTo('App\Models\Company');
    }

    public function carts() {

        return $this->hasMany('App\Models\Cart');
    }

    public function orderDetails() {

        return $this->hasMany('App\Models\OrderDetail');
    }

    public function favorite() {

        return $this->hasOne('App\Models\Favorite');
    }
}
