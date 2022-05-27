<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'content',
        'date',
        'latitude',
        'longitude'
    ];

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }
}
