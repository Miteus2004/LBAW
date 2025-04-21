<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function showUserBadges($userId)
    {
        $user = User::with('badges')->findOrFail($userId);
        return view('pages.user.show', compact('user'));
    }

}