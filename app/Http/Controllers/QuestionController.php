<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    // display a listing of questions
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'recent'); // Default sorting is by recent
    
        $query = Question::with(['user', 'tags'])
            ->withCount('answers')
            ->withCount([
                'votes as number_of_votes' => function ($query) {
                    $query->selectRaw("SUM(CASE WHEN vote_type = 'positive' THEN 1 WHEN vote_type = 'negative' THEN -1 ELSE 0 END)");
                }
            ]);
    
        switch ($sort) {
            case 'popular':
                $query->orderByDesc('number_of_votes');
                break;
            case 'answers':
                $query->orderByDesc('answers_count');
                break;
            case 'recent':
            default:
                $query->orderByDesc('last_edited'); 
                break;
        }
    
        $questions = $query->paginate(10);
    
        return view('pages.questions.index', compact('questions', 'sort'));
    }

    public function index_home(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            $timeframe = $request->input('timeframe'); // Get the selected timeframe
    
            // Base query
            $query = Question::with(['user', 'tags'])
                ->withCount([
                    'answers',
                    'votes as number_of_votes' => function ($query) {
                        $query->selectRaw("SUM(CASE WHEN vote_type = 'positive' THEN 1 WHEN vote_type = 'negative' THEN -1 ELSE 0 END)");
                    }
                ]);
        
            // Apply timeframe filter
            if ($timeframe === 'day') {
                $query->where('posted', '>=', now()->subDay());
            } elseif ($timeframe === 'week') {
                $query->where('posted', '>=', now()->subWeek());
            } elseif ($timeframe === 'month') {
                $query->where('posted', '>=', now()->subMonth());
            }
        
            // Sort by vote count and paginate
            $questions = $query->orderByDesc('number_of_votes')->paginate(10);
            
            return view('pages.unauthenticated_index', compact('questions'));
        }

        $followedTags = $user->follow_tags;

        $questions = Question::query();
    
        if ($request->has('tags')) {
            $selectedTags = $request->input('tags');
            $questions->whereHas('tags', function ($query) use ($selectedTags) {
                $query->whereIn('tags.id', $selectedTags);
            });
        } else {
            $selectedTags = $followedTags->pluck('id')->toArray();
            $questions->whereHas('tags', function ($query) use ($selectedTags) {
                $query->whereIn('tags.id', $selectedTags);
            });
        }
    
        if ($request->has('timeframe')) {
            $timeframe = $request->input('timeframe');
            switch ($timeframe) {
                case 'month':
                    $questions->where('posted', '>=', now()->subMonth());
                    break;
                case 'week':
                    $questions->where('posted', '>=', now()->subWeek());
                    break;
                case 'day':
                    $questions->where('posted', '>=', now()->subDay());
                    break;
            }
        }
    
        $questions = $questions->withCount('answers')->paginate(10);
    
        return view('pages.index', compact('questions', 'followedTags', 'selectedTags'));
    }
    
    // show the form for creating a new question
    public function create()
    {
        // Get all tags
        $tags = Tag::all();

        return view('pages.questions.create', compact('tags'));
    }

    // store a newly created question
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        // Create question
        $question = new Question();
        $question->user_id = Auth::id();
        $question->title = $request->input('title');
        $question->content = $request->input('content');
        $question->posted = now();
        $question->last_edited = now();
        $question->save();
        
        // Attach tags
        if ($request->has('tags')) {
            $tags = array_map('trim', explode(',', $request->input('tags')));
            $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();
            $question->tags()->attach($tagIds);
        }
        
        return redirect()->route('questions.show', $question->id);
    }

    // display the specified question
    public function show($id)
    {
        $question = Question::with([
            'user',
            'answers.user',
            'tags',
            'score',
            'answers.score',
        ])->findOrFail($id);

        $userVote = null;
        $userAnswerVotes = [];
        $bookmarked = false;

        if (Auth::check()) {
            // get user's vote on the question
            $vote = $question->get_votes()->where('user_id', Auth::id())->first();
            $userVote = $vote ? $vote->vote_type : null;

            // get user's votes on answers
            $userAnswerVotes = $question->answers->mapWithKeys(function ($answer) {
                $vote = $answer->get_votes()->where('user_id', Auth::id())->first();
                return [$answer->id => $vote ? $vote->vote_type : null];
            });

            // check if the question is bookmarked by the user
            $bookmarked = Auth::user()
                ->bookmarkedQuestionsWithKeys()
                ->where('question_id', $question->id)
                ->exists();
        }

        return view('pages.questions.show', compact('question', 'userVote', 'userAnswerVotes', 'bookmarked'));
    }

    // show the form for editing the specified question
    public function edit($id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('update', $question);

        $tags = Tag::all();

        return view('pages.questions.edit', compact('question', 'tags'));
    }

    // update the specified question
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('update', $question);

        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        // Update question
        $question->title = $request->input('title');
        $question->content = $request->input('content');
        $question->last_edited = now();
        $question->save();

        // Sync tags
        if ($request->has('tags')) {
            $tags = array_map('trim', explode(',', $request->input('tags')));
            $tagIds = Tag::whereIn('name', $tags)->pluck('id')->toArray();
            $question->tags()->sync($tagIds);
        }

        return redirect()->route('questions.show', $question->id);
    }

    // remove the specified question
    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('delete', $question);

        $question->delete();

        return redirect()->route('questions.index');
    }

    // vote on the question
    public function vote($id, Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 403);
        }

        $request->validate([
            'vote_type' => 'required|in:positive,negative',
        ]);

        $question = Question::findOrFail($id);

        if (Auth::id() === $question->user_id) {
            return response()->json(['message' => 'You cannot vote on your own question.'], 403);
        }

        $existingVote = $question->get_votes()->where('user_id', Auth::id())->first();

        if ($existingVote && $existingVote->vote_type === $request->input('vote_type')) {
            // if the user is voting the same way, remove the vote
            $question->get_votes()->where([
                ['user_id', Auth::id()],
                ['question_id', $id]
            ])->delete();
            $userVote = null;
        } else {
            // otherwise, update or create the vote
            $vote = $question->get_votes()->updateOrCreate(
                ['user_id' => Auth::id(), 'question_id' => $id],
                ['vote_type' => $request->input('vote_type')]
            );
            $userVote = $vote->vote_type;
        }

        $newVoteCount = $question->get_votes()->where('vote_type', 'positive')->count() -
                        $question->get_votes()->where('vote_type', 'negative')->count();

        return response()->json([
            'newVoteCount' => $newVoteCount,
            'userVote' => $userVote,
        ]);
    }

    // add a tag to the question
    public function tag($id, Request $request)
    {
        // validate tag
        $request->validate([
            'tag_id' => 'required|exists:tags,id',
        ]);

        $question = Question::findOrFail($id);

        // authorize user
        if (Auth::id() !== $question->user_id) {
            abort(403);
        }

        // attach tag
        $question->tags()->attach($request->input('tag_id'));

        return response()->json(['message' => 'Tag added successfully.']);
    }

    public function toggleBookmark($questionId)
    {
        $user = Auth::user();

        if ($user->bookmarkedQuestions()->where('question_id', $questionId)->exists()) {
            $user->bookmarkedQuestions()->detach($questionId);
            $bookmarked = false;
        } else {
            $user->bookmarkedQuestions()->attach($questionId);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }
}
