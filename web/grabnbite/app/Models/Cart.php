<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

     protected $fillable = [
        'user_id',
        'product_id',
        'qty',
        'addon_id',
        'has_addons'
    ];

    public function product() {

        return $this->belongsTo('App\Models\Product');
    }

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }
}
