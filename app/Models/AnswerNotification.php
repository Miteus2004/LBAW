<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerNotification extends Model
{
    protected $table = 'answer_notifications';

    protected $fillable = [
        'user_id',
        'answer_id',
        'not_date',
        'read_at',
    ];

    protected $dates = [
        'not_date',
        'read_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}