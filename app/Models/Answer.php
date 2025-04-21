<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'user_id',
        'content',
        'last_edited',
        'is_valid'
    ];

    protected $casts = [
        'posted' => 'datetime',
        'last_edited' => 'datetime',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(AnswerComment::class);
    }

    public function votes()
    {
        return $this->hasMany(AnswerScore::class);
    }


    // gets each vote on the answer, not just the number
    public function get_votes()
    {
        return $this->hasMany(AnswerVote::class);
    }
    
    public function score()
    {
        return $this->hasOne(AnswerScore::class, 'id', 'id');
    }
}
