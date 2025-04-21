<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerScore extends Model
{
    protected $table = 'answers_scores'; // Name of the SQL view
    public $timestamps = false; // Views usually don't have timestamps

    protected $guarded = [];
}
