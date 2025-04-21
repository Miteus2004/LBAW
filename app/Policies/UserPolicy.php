<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // checks if the user can update the profile
    public function update(User $user, User $model)
    {
        return $user->id === $model->id || (($user->user_role === 'administrator' || $user->user_role === 'moderator') && $model->user_role !== 'administrator');
    }

    // checks if the user can delete the profile
    public function delete(User $user, User $model)
    {
        return $user->id === $model->id || ($user->user_role === 'administrator' && $model->user_role !== 'administrator');
    }

    public function viewBookmarks(User $user, User $model)
    {
        return $user->id === $model->id || $user->user_role === 'administrator' || $user->user_role === 'moderator';
    }

    public function viewFollowTags(User $user, User $model)
    {
        return $user->id === $model->id || $user->user_role === 'administrator' || $user->user_role === 'moderator';
    }

    public function viewQuestionsAnswers(User $user, User $model)
    {
        return $user->id === $model->id || $user->user_role === 'administrator' || $user->user_role === 'moderator';
    }

    public function create(User $user)
    {
        return $user->user_role === 'administrator';
    }

    public function ban(User $admin, User $user)
    {
        return ($admin->user_role === 'administrator' || $admin->user_role === 'moderator') && $user->user_role !== 'administrator' && $user->user_role !== 'banned_user';
    }

    public function unban(User $admin, User $user)
    {
        return ($admin->user_role === 'administrator' || $admin->user_role === 'moderator') && $user->user_role === "banned_user";
    }
    
    public function change_role(User $user, User $model)
    {
        if($model->user_role === 'administrator') {
            return false;
        }
        return $user->user_role === 'administrator' && $model->user_role !== 'banned_user';
    }
}