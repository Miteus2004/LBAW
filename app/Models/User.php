<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // don't add create and update timestamps in database
    public $timestamps  = false;

    protected $fillable = [
        'username',
        'email',
        'hashed_password',
        'bio',
        'user_role',
        'image_url',
        'is_anonymous',
    ];

    // attributes that should be hidden
    protected $hidden = [
        'hashed_password',
        'remember_token',
    ];

    // the attributes that should be cast
    protected $casts = [
        'email_verified_at' => 'datetime',
        'hashed_password' => 'hashed',
        'is_anonymous' => 'boolean',
    ];

    // change the DB column to compare password
    public function getAuthPassword()
    {
        return $this->hashed_password;
    }

    // get the questions for the user
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    // get the answers for the user
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    // get the comments to questions for the user
    public function questions_comments(): HasMany
    {
        return $this->hasMany(QuestionComment::class);
    }

    // get the comments to answers for the user
    public function answer_comments(): HasMany
    {
        return $this->hasMany(AnswerComment::class);
    }

    // get the votes for the user
    public function votes(): HasMany
    {
        return $this->hasMany(QuestionScore::class);
    }

    // get the badges for the user
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id');
    }
    
    public function answerNotifications()
    {
        return $this->hasMany(AnswerNotification::class);
    }

    public function question_commentNotifications()
    {
        return $this->hasMany(QuestionCommentNotification::class);
    }

    public function answer_commentNotifications()
    {
        return $this->hasMany(AnswerCommentNotification::class);
    }

    public function bookmarkedQuestions()
    {
        return $this->belongsToMany(Question::class, 'bookmarks', 'user_id', 'question_id');
    }

    public function follow_tags()
    {
        return $this->belongsToMany(Tag::class, 'follow_tags', 'user_id', 'tag_id');
    }

    public function bookmarkedQuestionsWithKeys()
    {
        return $this->belongsToMany(Question::class, 'bookmarks', 'user_id', 'question_id');
    }
    
    public function isAdmin(): bool
    {
        // Assuming you have a 'role' attribute where 'admin' signifies admin users
        return $this->role === 'admin';
    }

    public function userScore()
    {
        return $this->hasOne(UserScores::class, 'user_id', 'id');
    }

    public function getReputationAttribute()
    {
        return $this->userScore?->total_score ?? 0; 
    }
}