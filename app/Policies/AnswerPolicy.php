<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;

class AnswerPolicy
{
    /**
     * Determine whether the user can update the answer.
     */
    public function update(User $user, Answer $answer)
    {
        return $user->id === $answer->user_id;
    }

    /**
     * Determine whether the user can delete the answer.
     */
    public function delete(User $user, Answer $answer)
    {
        return $user->id === $answer->user_id || $user->user_role === 'administrator';
    }

    /**
     * Determine whether the user can vote on the answer.
     */
    public function vote(User $user, Answer $answer)
    {
        return $user->id !== $answer->user_id;
    }

    /**
     * Determine whether the user can mark the answer as valid.
     */
    public function markAsValid(User $user, Answer $answer)
    {
        return $user->id === $answer->question->user_id || $user->user_role === 'administrator';
    }
}