<?php

// app/Models/Tag.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_tags');
    }

    public function get_follow()
    {
        return $this->belongsToMany(User::class, 'follow_tags', 'tag_id', 'user_id');
    }
}