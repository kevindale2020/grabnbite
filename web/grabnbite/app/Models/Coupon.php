<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
    	'company_id',
        'code',
        'description',
        'value',
        'constraint',
        'type'
    ];

    public function company() {

        return $this->belongsTo('App\Models\Company');
    }

    public function order() {

        return $this->hasOne('App\Models\Order');
    }
}
