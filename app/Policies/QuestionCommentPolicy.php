<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuestionComment;

class QuestionCommentPolicy
{
    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, QuestionComment $questionComment)
    {
        return $user->id === $questionComment->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, QuestionComment $questionComment)
    {
        return $user->id === $questionComment->user_id || $user->user_role === 'administrator';
    }
}