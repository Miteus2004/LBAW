<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class TagController extends Controller
{
    /**
     * Display a listing of all tags.
     */
    public function index()
    {
        $tags = Tag::withCount('questions')->paginate(10);

        return view('pages.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('pages.tags.create');
    }

    /**
     * Store a newly created tag in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name|max:255',
        ]);

        Tag::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('tags.index')->with('status', 'Tag added successfully.');
    }

    /**
     * Display the specified tag.
     */
    public function show(Tag $tag)
    {
        $questions = $tag->questions()->paginate(10);

        return view('pages.tags.show', compact('tag', 'questions'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(Tag $tag)
    {
        return view('pages.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|unique:tags,name,' . $tag->id . '|max:255',
        ]);

        $tag->update($request->only('name'));

        return redirect()->route('tags.index');
    }

    /**
     * Remove the specified tag from the database.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')->with('status', 'Tag deleted successfully.');
    }

    public function follow(Tag $tag)
    {
        Auth::user()->follow_tags()->attach($tag->id);
        return redirect()->back()->with('status', 'Tag followed successfully.');
    }

    public function unfollow(Tag $tag)
    {
        Auth::user()->follow_tags()->detach($tag->id);
        return redirect()->back()->with('status', 'Tag unfollowed successfully.');
    }
}