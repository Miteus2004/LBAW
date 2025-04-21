<?php

namespace App\Http\Controllers;

use App\Models\AnswerComment;
use Illuminate\Http\Request;

class AnswerCommentController extends Controller
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
            'answer_id' => 'required|exists:answers,id',
            'content' => 'required|string',
        ]);    
   
        $answerComment = AnswerComment::create([
            'answer_id' => $request->answer_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
            'posted' => now(),
            'last_edited' => now(),
        ]);

        return redirect()->route('questions.show', $answerComment->answer->question_id);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $answerComment = AnswerComment::findOrFail($id);
        $this->authorize('update', $answerComment);

        $answerComment->update([
            'content' => $request->content,
            'posted' => now(),
        ]);

        return redirect()->route('questions.show', $answerComment->answer->question_id);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $answerComment = AnswerComment::findOrFail($id);
        $this->authorize('delete', $answerComment);

        $answerComment->delete();

        return redirect()->route('questions.show', $answerComment->answer->question_id);  
    }
}
