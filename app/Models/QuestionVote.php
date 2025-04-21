<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionVote extends Model
{
    protected $table = 'questions_votes';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'vote_type',
        'question_id',
        'user_id',
    ];
}