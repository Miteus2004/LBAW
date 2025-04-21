<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AnswerComment;

class AnswerCommentPolicy
{
    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, AnswerComment $answerComment)
    {
        return $user->id === $answerComment->user_id;
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, AnswerComment $answerComment)
    {
        return $user->id === $answerComment->user_id || $user->user_role === 'administrator';
    }
}