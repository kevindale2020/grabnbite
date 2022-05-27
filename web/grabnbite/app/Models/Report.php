<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $primaryKey = 'id';

    protected $fillable = [
        'date',
        'total_earnings',
        'company_id'
    ];
}
