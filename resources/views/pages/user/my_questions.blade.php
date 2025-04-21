@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Questions</h2>
    @if($questions->isEmpty())
        <p>You have not posted any questions yet.</p>
    @else
        @foreach($questions as $question)
            @include('partials.element_of_list_of_questions', ['question' => $question])
        @endforeach

        {{ $questions->links() }}
    @endif
</div>
@endsection