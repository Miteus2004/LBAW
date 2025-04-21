<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model
{
    protected $table = 'answers_votes';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'vote_type',
        'answer_id',
        'user_id',
    ];
}