<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'user_id',
        'rate',
        'feedback',
        'rated_date',
        'subject_type'
    ];

    public function user() {

    	return $this->belongsTo('App\Models\User');
    }

     public function company() {

        return $this->belongsTo('App\Models\Company');
    }
}
