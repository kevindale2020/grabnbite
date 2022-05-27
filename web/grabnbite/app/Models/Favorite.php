<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
    	'user_id',
        'product_id',
        'date_added'
    ];

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }

    public function product() {

    	return $this->belongsTo('App\Models\Product');
    }
}
