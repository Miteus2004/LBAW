@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Answers</h2>
    @if($answers->isEmpty())
        <p>You have not posted any answers yet.</p>
    @else
        @foreach($answers as $answer)
            <div class="card mb-3">
                <div class="card-body">
                    <p>{{ $answer->content }}</p>
                    <a href="{{ route('questions.show', $answer->question->id) }}" class="btn btn-link">
                        View Question: {{ $answer->question->title }}
                    </a>
                    <p class="text-muted">Posted {{ $answer->posted->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach

        {{ $answers->links() }}
    @endif
</div>
@endsection