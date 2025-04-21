<?php

namespace App\Policies;

use App\Models\User;

class PagesPolicy
{
    /**
     * Determine if the user can create tags.
     */
    public function createTag(User $user)
    {
        return $user->user_role === 'administrator';
    }

    public function delete(User $user)
    {
        return $user->user_role === 'administrator';
    }
}