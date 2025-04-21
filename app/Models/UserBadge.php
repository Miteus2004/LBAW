<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    protected $table = 'user_badges';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'badge_id',
    ];

    // get the user that owns the badge
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // get the badge that belongs to the user
    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class);
    }
}
