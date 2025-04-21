<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;

class SearchController extends Controller
{
    public function searchQuestions(Request $request)
    {
        // Validate the search input
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        // Retrieve and sanitize the search query
        $query = trim($request->input('query'));
        $query = filter_var($query, FILTER_SANITIZE_STRING);

        // Perform the search on the questions table using DB::raw()
        $questions = Question::whereRaw("tsvectors @@ plainto_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, plainto_tsquery('english', ?)) DESC", [$query])
            ->with(['user', 'tags'])
            ->withCount('answers') 
            ->paginate(10) 
            ->appends(['query' => $query]);

        // Return the search results view with the questions
        return view('search.results', compact('questions', 'query'));
    }
}
