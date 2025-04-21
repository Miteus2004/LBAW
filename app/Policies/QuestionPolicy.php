<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

class QuestionPolicy
{
    /**
     * Determine if the user can update the question.
     */
    public function update(User $user, Question $question)
    {
        return $user->id === $question->user_id || $user->user_role === 'administrator' || $user->user_role === 'moderator';
    }

    /**
     * Determine if the user can delete the question.
     */
    public function delete(User $user, Question $question)
    {
        return $user->id === $question->user_id || $user->user_role === 'administrator' || $user->user_role === 'moderator';
    }

    /**
     * Determine if the user can view the question.
     */
    public function view(User $user, Question $question)
    {
        return true; // Adjust based on your application's needs
    }

    /**
     * Determine if the user can create a question.
     */
    public function create(User $user)
    {
        return $user->hasRole(['authenticated_user', 'administrator', 'moderator']);
    }
}