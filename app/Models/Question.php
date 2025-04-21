<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    // specify the table if it's not the default 'questions'
    protected $table = 'questions';

    // disable timestamps if not used
    public $timestamps = false;

    // define fillable attributes
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'posted',
        'last_edited',
        'tsvectors',
    ];

    protected $casts = [
        'posted' => 'datetime',
        'last_edited' => 'datetime',
    ];

    // user who asked the question
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // answers related to the question
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function score()
    {
        return $this->hasOne(QuestionScore::class, 'id', 'id');
    }

    // comments on the question
    public function comments(): HasMany
    {
        return $this->hasMany(QuestionComment::class);
    }

    // number of votes on the question
    public function votes(): HasMany
    {
        return $this->hasMany(QuestionVote::class);
    }

    // tags associated with the question
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'question_tags', 'question_id', 'tag_id');
    }

    // gets each vote on the question, not just the number
    public function get_votes()
    {
        return $this->hasMany(QuestionVote::class);
    }

    // get each bookmark on the question, not just the number
    public function get_bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function getNumberOfVotesAttribute()
    {
        return $this->votes()
            ->selectRaw("SUM(CASE WHEN vote_type = 'positive' THEN 1 WHEN vote_type = 'negative' THEN -1 ELSE 0 END) as number_of_votes")
            ->pluck('number_of_votes')
            ->first();
    }
}