<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerCommentNotification extends Model
{
        protected $table = 'answer_comment_notifications';

    protected $fillable = [
        'user_id',
        'answer_comment_id',
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

    public function answer_comment()
    {
        return $this->belongsTo(AnswerComment::class);
    }}
