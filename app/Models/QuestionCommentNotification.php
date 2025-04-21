<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionCommentNotification extends Model
{
    protected $table = 'question_comment_notifications';

    protected $fillable = [
        'user_id',
        'question_comment_id',
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

    public function question_comment()
    {
        return $this->belongsTo(QuestionComment::class);
    }}
