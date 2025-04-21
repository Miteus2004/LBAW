<!-- has 2 columns -->
<!-- the first colown is smaller and it's first row displays how many votes the question has representing the question score: it says the question score and then says votes -->
<!-- the second row of the first column displays the number of answers that question has -->
<!-- the second column is bigger and it's first row displays the title of the question -->
<!-- the second row displays the start of the question -->
<!-- the third row displays the tags of the question (by calling another partial view called list_of_tag_buttons.blade.php with the appropriate arguments) -->
<!-- should take the question id it refers to as an argument and then go get the information from that -->

@props(['question'])

<div class="row py-3">
    <!-- first column (votes == score and number of answers) -->
    <div class="col-md-2 text-center">
        <!-- question score == question votes -->
        <div>
            <h4 class="m-0">{{ $question->number_of_votes ?? 0 }}</h4>
            <small>votes</small>
        </div>
        <!-- number of answers -->
        <div class="mt-3">
            <h4 class="m-0">{{ $question->answers_count }}</h4>
            <small>answers</small>
        </div>
    </div>

    <!-- second column (title, description, tags) -->
    <div class="col-md-10">
        <!-- question title -->
        <div>
            <a href="{{ route('questions.show', $question->id) }}" class="h5 text-decoration-none">
                {{ $question->title }}
            </a>
        </div>
        <!-- question description -->
        <div class="mt-2">
            <p class="text-muted mb-0">
                {{ Str::limit(strip_tags($question->content), 150) }}
            </p>
        </div>
        <!-- Asked by -->
        <div class="mt-2">
            <p class="align-items-center">
                <strong>Asked:</strong> {{ $question->last_edited->diffForHumans() }} by
                <span class="ms-1" style="position: relative; top: -2px;">
                    @include('partials.user_button_with_reputation', ['user' => $question->user])
                </span>
            </p>
        </div>
        <!-- Delete Button -->
        @can('delete', $question)
            <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="mt-3"
                onsubmit="return confirm('Are you sure you want to delete this question?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt mr-1"></i> Delete Question
                </button>
            </form>
        @endcan
        <!-- tags -->
        <div class="mt-2">
            @include('partials.list_of_tag_buttons', ['tags' => $question->tags])
        </div>
    </div>
</div>