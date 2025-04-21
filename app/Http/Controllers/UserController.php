<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // Adjust pagination as needed

        return view('pages.user.index', compact('users'));
    }
    
    // show the specified user profile
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user.show', compact('user'));
    }

    // show the form for editing the specified user profile
    public function edit($id)
    {
    $user = User::findOrFail($id);

    if (Auth::id() !== $user->id && Auth::user()->user_role !== 'administrator') {
        return redirect()->route('users.show', Auth::id())->with('error', 'You are not authorized to edit this profile.');
    }

    return view('pages.user.edit', compact('user'));
    }
    

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->bio = $request->input('bio');

        $hasImage = $request->hasFile('image');
        if ($hasImage) {
            $imagePath = $request->file('image')->store('profile_images', 'public');
            $user->image_url = Storage::url($imagePath);
        }

        $user->save();

        return redirect()->route('users.show', $user->id)->with('success', 'Profile updated successfully.');
    }

    // delete the specified user profile
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        // Mark the user as anonymous
        $user->update([
            'username' => 'Anonymous',
            'email' => null,
            'hashed_password' => null,
            'bio' => null,
            'image_url' => null,
            'is_anonymous' => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User anonymized successfully.');
    }

    public function showBookmarks($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('viewBookmarks', $user);

        $bookmarks = $user->bookmarkedQuestions()->paginate(10); // Adjust the number as needed

        return view('pages.user.bookmarks', compact('user', 'bookmarks'));
    }

    public function showFollowed_Tags($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('viewFollowTags', $user);

        $follow_tags = $user->follow_tags()->paginate(10); // Adjust the number as needed

        return view('pages.user.follow_tags', compact('user', 'follow_tags'));
    }

    public function myQuestions($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('viewQuestionsAnswers', $user);
        $questions = $user->questions()->with(['tags'])->paginate(10);

        return view('pages.user.my_questions', compact('questions'));
    }

    public function myAnswers($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('viewQuestionsAnswers', $user);
        $answers = $user->answers()->with(['question'])->paginate(10);

        return view('pages.user.my_answers', compact('answers'));
    }

    // an admin can create a new user
    public function create()
    {
        $this->authorize('create', User::class);
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        if($request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:authenticated_user,moderator,administrator',
        ]));

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'hashed_password' => Hash::make($request->password),
            'user_role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('ban', $user);

        $user->user_role = 'banned_user';
        $user->save();

        return redirect()->route('users.index')->with('success', 'User has been banned successfully.');
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);

        $this->authorize('unban', $user);

        $user->user_role = 'authenticated_user';
        $user->save();

        return redirect()->route('users.index')->with('success', 'User has been unbanned successfully.');
    }

    public function changeRole($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('change_role', $user);

        if ($user->user_role === 'authenticated_user') {
            $user->user_role = 'moderator';
        } elseif ($user->user_role === 'moderator') {
            $user->user_role = 'authenticated_user';
        }

        $user->save();

        return redirect()->route('users.index', $user->id)->with('success', 'User role updated successfully.');
    }
}