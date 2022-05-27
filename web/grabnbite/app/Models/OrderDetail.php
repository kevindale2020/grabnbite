<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

     protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'addon_id'
    ];

    public function order() {

    	return $this->belongsTo('App\Models\Order');
    }

    public function product() {

    	return $this->belongsTo('App\Models\Product');
    }
}
