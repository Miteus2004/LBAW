<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'content' => 'required|string',
        ]);

        $answer = Answer::create([
            'question_id' => $request->question_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'posted' => now(),
            'last_edited' => now(),
        ]);

        return redirect()->route('questions.show', $answer->question_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $answer = Answer::findOrFail($id);
        $this->authorize('update', $answer);

        $answer->update([
            'content' => $request->content,
            'posted' => now(),
        ]);

        return redirect()->route('questions.show', $answer->question_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $answer = Answer::findOrFail($id);
        $this->authorize('delete', $answer);

        $answer->delete();

        return redirect()->route('questions.show', $answer->question_id);
    }

    public function vote(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 403);
        }

        $request->validate([
            'vote_type' => 'required|in:positive,negative',
        ]);

        $answer = Answer::findOrFail($id);

        if (Auth::id() === $answer->user_id) {
            return response()->json(['message' => 'You cannot vote on your own answer.'], 403);
        }

        $existingVote = $answer->get_votes()->where('user_id', Auth::id())->first();

        if ($existingVote && $existingVote->vote_type === $request->input('vote_type')) {
            // if the user is voting the same way, remove the vote
            $answer->get_votes()->where([
                ['user_id', Auth::id()],
                ['answer_id', $id]
            ])->delete();
            $userVote = null;
        } else {
            // otherwise, update or create the vote
            $vote = $answer->get_votes()->updateOrCreate(
                ['user_id' => Auth::id(), 'answer_id' => $id],
                ['vote_type' => $request->input('vote_type')]
            );
            $userVote = $vote->vote_type;
        }

        $newVoteCount = $answer->get_votes()->where('vote_type', 'positive')->count() -
                        $answer->get_votes()->where('vote_type', 'negative')->count();

        return response()->json([
            'newVoteCount' => $newVoteCount,
            'userVote' => $userVote,
        ]);
    }

    public function markAsValid($id)
    {
        $answer = Answer::findOrFail($id);
        $this->authorize('markAsValid', $answer);

        $answer->update(['is_valid' => true]);

        return redirect()->route('questions.show', $answer->question_id);
    }

    public function unmarkAsValid($id)
    {
        $answer = Answer::findOrFail($id);
        $this->authorize('markAsValid', $answer);

        $answer->update(['is_valid' => false]);

        return redirect()->route('questions.show', $answer->question_id);
    }
}
