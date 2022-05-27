<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    public $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'vehicle_type',
        'vehicle_color',
        'vehicle_plate_no',
        'tin',
        'bir_form',
        'gov_issued_id',
        'driver_license'
    ];

    public function user() {

        return $this->belongsTo('App\Models\User');
    }
}
