<?php

namespace App\Http\Controllers;

use App\Models\QuestionComment;
use Illuminate\Http\Request;

class QuestionCommentController extends Controller
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
   
        $questionComment = QuestionComment::create([
            'question_id' => $request->question_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'posted' => now(),
            'last_edited' => now(),
        ]);

        return redirect()->route('questions.show', $questionComment->question_id);    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionComment $questionComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionComment $questionComment)
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

        $questionComment = QuestionComment::findOrFail($id);
        $this->authorize('update', $questionComment);

        $questionComment->content = $request->content;
        $questionComment->posted = now();
        $questionComment->save();

        return redirect()->route('questions.show', $questionComment->question_id);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $questionComment = QuestionComment::findOrFail($id);

        $this->authorize('delete', $questionComment);

        $questionComment->delete();

        return redirect()->route('questions.show', $questionComment->question_id);   
    }
}
