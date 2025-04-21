<!-- show a specific question -->
<!-- needs put the title of the question -->
<!-- in the next row it should say how much time has passed since the question was asked (example: 1 min ago, 3 hours ago, 1 day ago, 10 days ago, 1 month ago, 2 years ago, ...) -->
<!-- then it should display the tags by doing Tags: then it should display the list of the tags using the partial view list_of_tag_buttons.blade.php -->
<!-- then it should display the view vote_with_bookmark, passing the appropriate arguments -->
<!-- and to the right of that view it should display the description of the question and below it the name of the user who posted the question and his reputation by using the partial view user_button_with_reputation.blade.php, it then needs to add a separator and list all the comments, one by one with a separator in between and also putting a partial view user_button_with_reputation.blade.php of the user who posted that comment and after the final one there should be a button saying add comment and if the user is a visitor it should redirect him to the log in page, otherwise it should allow him to write a comment and press submit, in each comment if the user is also the owner of that comment he can edit or delete it, if he's the administrator or moderator he can delete even the ones that aren't his -->
<!-- now there should be a text saying answers -->
<!-- if there are no answers it should just have a button write an answer that opens a form that allows the user to write an answer and submit it -->
<!-- if there are answers, they should be ordered decreasing order of upvotes and they should have the partial view vote.blade.php, and to the right of it, it should have the answer and below the answer it should have the partial view user_button_with_reputation.blade.php -->
<!-- below that right part and still to the right of the vote partial page, it should have the comments, just like they are in the answer, if there are no comments it should just say add a comment and allow the user to do so by submitting a form and if there are comments it should display the comments, by displaying the comment content and the name and reputation of the user who commented below it, using the view user_button_with_reputation.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container my-4">

    <!-- question title -->
    <h2>{{ $question->title }}</h2>

    <!-- time since posted -->
    <div class="text-muted mb-2">
        Asked {{ $question->last_edited->diffForHumans() }} at {{ $question->last_edited->format('H:i') }}
        @if ($question->posted != $question->last_edited)
            <span>(edited)</span>
        @endif
    </div>

    <!-- tags -->
    <div class="mb-3">
        Tags:
        @include('partials.list_of_tag_buttons', ['tags' => $question->tags])
    </div>

    <div class="row">
        <!-- votes and bookmark -->
        <div class="col-md-1 text-center">
            @include('partials.vote_with_bookmark', ['item' => $question, 'userVote' => $userVote ?? null, 'bookmarked' => $bookmarked])
        </div>
        <!-- question content -->
        <div class="col-md-11">
            <div class="mb-3">
                {!! nl2br(e($question->content)) !!}
            </div>

            <!-- user info -->
            <div class="d-flex justify-content-end">
                @include('partials.user_button_with_reputation', ['user' => $question->user])
            </div>

            <!-- Edit and Delete Question Buttons -->
            @can('update', $question)
                <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-primary mt-3">
                    <i class="fas fa-edit mr-1"></i> Edit Question
                </a>
            @endcan

            @can('delete', $question)
                <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Are you sure you want to delete this question? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mt-3">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Question
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <hr>

    <!-- question comments -->
    <div class="comments mt-3">
    @foreach($question->comments as $comment)
        <div class="d-flex">
            <div class="flex-grow-1">
                <div id="comment-content-{{ $comment->id }}">
                    {{ $comment->content }}
                    <div class="text-muted">
                        Commented by {{ $comment->user->name }}
                        @if ($comment->last_edited)
                            {{ $comment->last_edited->diffForHumans() }} at {{ $comment->last_edited->format('H:i') }}
                        @else
                            {{ $comment->posted->diffForHumans() }} at {{ $comment->posted->format('H:i') }}
                        @endif
                        @if ($comment->posted != $comment->last_edited)
                            <span>(edited)</span>
                        @endif
                    </div>
                </div>
                <!-- edit comment form (initially hidden) -->
                <div id="edit-comment-form-{{ $comment->id }}" style="display: none;">
                    <form method="POST" action="{{ route('question_comments.update', $comment->id) }}">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <textarea name="content" class="form-control" rows="2">{{ $comment->content }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="hideEditCommentForm({{ $comment->id }})">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </form>
                </div>
            </div>
            <div class="ml-2">
                @include('partials.user_button_with_reputation', ['user' => $comment->user])
            </div>
        </div>
        @can('update', $comment)
            <button id="edit-comment-btn-{{ $comment->id }}" class="btn btn-primary mt-3" onclick="showEditCommentForm({{ $comment->id }})">
                <i class="fas fa-edit mr-1"></i> Edit Comment
            </button>
        @endcan
        @can('delete', $comment)
        <form method="POST" action="{{ route('question_comments.destroy', $comment->id) }}" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt mr-1"></i> Delete Comment
            </button>
        </form>
        @endcan
        <hr>
    @endforeach

    <!-- add comment -->
    @auth
        <button id="add-comment-btn" class="btn btn-primary mt-3" onclick="showCommentForm()">
            <i class="fas fa-pencil-alt mr-1"></i> Add Comment
        </button>
        <!-- comment form (initially hidden) -->
        <div id="comment-form" class="mt-3" style="display: none;">
            <form method="POST" action="{{ route('question_comments.store') }}">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="2" placeholder="Add a comment"></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Comment
                </button>
            </form>
        </div>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">
            <i class="fas fa-pencil-alt mr-1"></i> Add Comment
        </a>
    @endauth
    </div>

    <hr>

    <!-- answers section -->
    <h3 class="mt-4">{{ $question->answers->count() }} Answers</h3>

    @if(!$question->answers->isEmpty())
        <!-- list of answers -->
        @foreach($question->answers->sortByDesc('score') as $answer)
            <div class="row mt-4">
                <!-- votes -->
                <div class="col-md-1 text-center">
                    @include('partials.vote', ['item' => $answer, 'userVote' => $userAnswerVotes[$answer->id] ?? null, 'itemType' => 'answer'])
                </div>
                <!-- answer content -->
                <div class="col-md-11">
                    <div id="answer-content-{{ $answer->id }}" class="mb-3">
                        {!! nl2br(e($answer->content)) !!}
                        <div class="text-muted">
                            Answered {{ $answer->last_edited->diffForHumans() }} at {{ $answer->last_edited->format('H:i') }}
                            @if ($answer->posted != $answer->last_edited)
                                <span>(edited)</span>
                            @endif
                        </div>
                        <!-- answer user info (it's here so it's also hidden when we are editing the answer) -->
                        <div class="d-flex justify-content-end">
                            @include('partials.user_button_with_reputation', ['user' => $answer->user])
                        </div>
                    </div>

                    @can('markAsValid', $answer)
                        @if(!$answer->is_valid)
                            <form method="POST" action="{{ route('answers.markAsValid', $answer->id) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check mr-1"></i> Mark as Valid Answer
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('answers.unmarkValid', $answer->id) }}" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-times mr-1"></i> Unmark as Valid Answer
                                </button>
                            </form>
                        @endif
                    @endcan

                    @if($answer->is_valid)
                        <span class="badge bg-success text-white fs-4 px-2 py-2 mt-1">Valid Answer!</span>
                    @endif

                    <!-- edit answer form (initially hidden) -->
                    <div id="edit-answer-form-{{ $answer->id }}" style="display: none;">
                        <form method="POST" action="{{ route('answers.update', $answer->id) }}">
                            @csrf
                            <div class="form-group">
                                <textarea name="content" class="form-control" rows="5">{{ $answer->content }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideEditAnswerForm({{ $answer->id }})">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-start mt-3">
                        @can('update', $answer)
                            <button id="edit-answer-btn-{{ $answer->id }}" class="btn btn-primary me-2" onclick="showEditAnswerForm({{ $answer->id }})">
                                <i class="fas fa-edit mr-1"></i> Edit Answer
                            </button>
                        @endcan
                        @can('delete', $answer)
                            <form method="POST" action="{{ route('answers.destroy', $answer->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete Answer
                                </button>
                            </form>
                        @endcan
                    </div>

                    <!-- answer comments -->
                    <div class="comments mt-3">
                        @foreach($answer->comments as $comment)
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div id="answer-comment-content-{{ $comment->id }}">
                                        {{ $comment->content }}
                                        <div class="text-muted">
                                            Commented by {{ $comment->user->name }}
                                            @if ($comment->last_edited)
                                                {{ $comment->last_edited->diffForHumans() }} at {{ $comment->last_edited->format('H:i') }}
                                            @else
                                                {{ $comment->posted->diffForHumans() }} at {{ $comment->posted->format('H:i') }}
                                            @endif
                                            @if ($comment->posted != $comment->last_edited)
                                                <span>(edited)</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- edit comment form (initially hidden) -->
                                    <div id="edit-answer-comment-form-{{ $comment->id }}" style="display: none;">
                                        <form method="POST" action="{{ route('answer_comments.update', $comment->id) }}">
                                            @csrf
                                            <div class="form-group">
                                                <textarea name="content" class="form-control" rows="2">{{ $comment->content }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save mr-1"></i> Save Changes
                                            </button>
                                            <button type="button" class="btn btn-secondary" onclick="hideEditAnswerCommentForm({{ $comment->id }})">
                                                <i class="fas fa-times mr-1"></i> Cancel
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    @include('partials.user_button_with_reputation', ['user' => $comment->user])
                                </div>
                            </div>
                            @can('update', $comment)
                                <button id="edit-answer-comment-btn-{{ $comment->id }}" class="btn btn-primary mt-3" onclick="showEditAnswerCommentForm({{ $comment->id }})">
                                    <i class="fas fa-edit mr-1"></i> Edit Comment
                                </button>
                            @endcan
                            @can('delete', $comment)
                            <form method="POST" action="{{ route('answer_comments.destroy', $comment->id) }}" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete Comment
                                </button>
                            </form>
                            @endcan
                            <hr>
                        @endforeach

                        <!-- add comment to answer -->
                        @auth
                            <button id="add-answer-comment-btn-{{ $answer->id }}" class="btn btn-primary mt-3" onclick="showAnswerCommentForm({{ $answer->id }})">
                                <i class="fas fa-pencil-alt mr-1"></i> Add Comment
                            </button>
                            <!-- answer comment form (initially hidden) -->
                            <div id="answer-comment-form-{{ $answer->id }}" class="mt-3" style="display: none;">
                                <form method="POST" action="{{ route('answer_comments.store') }}">
                                    @csrf
                                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                                    <div class="form-group">
                                        <textarea name="content" class="form-control" rows="2" placeholder="Add a comment"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane mr-1"></i> Submit Comment
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-pencil-alt mr-1"></i> Add Comment
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    @auth
        <button id="write-answer-btn" class="btn btn-primary mt-3" onclick="showAnswerForm()">
            <i class="fas fa-pencil-alt mr-1"></i> Write an Answer
        </button>
        <!-- answer form (initially hidden) -->
        <div id="answer-form" class="mt-3" style="display: none;">
            <form method="POST" action="{{ route('answers.store') }}">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <div class="form-group">
                    <textarea name="content" class="form-control" rows="5" placeholder="Your answer"></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Answer
                </button>
            </form>
        </div>
    @else
        <a href="{{ route('login') }}" class="btn btn-primary mt-3">
            <i class="fas fa-pencil-alt mr-1"></i> Write an Answer
        </a>
    @endauth

    <!-- padding just so the write an answer button or the submit answer button aren't touching the bottom of the page -->
    <div class="pb-3"></div>
</div>

<!-- JavaScript for showing the forms -->
<script>
    function showCommentForm() {
        document.getElementById('add-comment-btn').style.display = 'none';
        document.getElementById('comment-form').style.display = 'block';
    }

    function showAnswerCommentForm(answerId) {
        document.getElementById('add-answer-comment-btn-' + answerId).style.display = 'none';
        document.getElementById('answer-comment-form-' + answerId).style.display = 'block';
    }

    function showAnswerForm() {
        document.getElementById('write-answer-btn').style.display = 'none';
        document.getElementById('answer-form').style.display = 'block';
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    }

    function showEditAnswerForm(answerId) {
        document.getElementById('edit-answer-btn-' + answerId).style.display = 'none';
        document.getElementById('answer-content-' + answerId).style.display = 'none';
        document.getElementById('edit-answer-form-' + answerId).style.display = 'block';
    }

    function hideEditAnswerForm(answerId) {
        document.getElementById('edit-answer-btn-' + answerId).style.display = 'block';
        document.getElementById('answer-content-' + answerId).style.display = 'block';
        document.getElementById('edit-answer-form-' + answerId).style.display = 'none';
    }

    function showEditCommentForm(commentId) {
        document.getElementById('edit-comment-btn-' + commentId).style.display = 'none';
        document.getElementById('comment-content-' + commentId).style.display = 'none';
        document.getElementById('edit-comment-form-' + commentId).style.display = 'block';
    }

    function hideEditCommentForm(commentId) {
        document.getElementById('edit-comment-btn-' + commentId).style.display = 'block';
        document.getElementById('comment-content-' + commentId).style.display = 'block';
        document.getElementById('edit-comment-form-' + commentId).style.display = 'none';
    }

    function showEditQuestionCommentForm(commentId) {
        document.getElementById('edit-question-comment-btn-' + commentId).style.display = 'none';
        document.getElementById('question-comment-content-' + commentId).style.display = 'none';
        document.getElementById('edit-question-comment-form-' + commentId).style.display = 'block';
    }

    function hideEditQuestionCommentForm(commentId) {
        document.getElementById('edit-question-comment-btn-' + commentId).style.display = 'block';
        document.getElementById('question-comment-content-' + commentId).style.display = 'block';
        document.getElementById('edit-question-comment-form-' + commentId).style.display = 'none';
    }

    function showEditAnswerCommentForm(commentId) {
        document.getElementById('edit-answer-comment-btn-' + commentId).style.display = 'none';
        document.getElementById('answer-comment-content-' + commentId).style.display = 'none';
        document.getElementById('edit-answer-comment-form-' + commentId).style.display = 'block';
    }

    function hideEditAnswerCommentForm(commentId) {
        document.getElementById('edit-answer-comment-btn-' + commentId).style.display = 'block';
        document.getElementById('answer-comment-content-' + commentId).style.display = 'block';
        document.getElementById('edit-answer-comment-form-' + commentId).style.display = 'none';
    }
</script>
@endsection