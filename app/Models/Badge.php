<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    // disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'score_necessary',
        'score_necessary_type',
        'badge_name',
    ];
}