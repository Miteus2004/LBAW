<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class AnswerComment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'answer_id',
        'user_id',
        'content',
        'posted',
        'last_edited',
        'tsvectors',
    ];

    protected $casts = [
        'posted' => 'datetime',
        'last_edited' => 'datetime',
    ];

    // user who commented
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
