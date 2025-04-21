@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">All Questions</h1>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Create New Question Button and Sorting Options -->
    <div class="d-flex justify-content-between mb-4">
        <div class="mb-4 text-end">
            <a href="{{ route('questions.create') }}" class="btn btn-primary">
                <i class="fas fa-question mr-1"></i> Ask a New Question
            </a>
        </div>
        <div>
            <form action="{{ route('questions.index') }}" method="GET" class="form-inline">
                <label for="sort" class="mr-2">Sort by:</label>
                <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                    <option value="recent" {{ $sort == 'recent' ? 'selected' : '' }}>Recent</option>
                    <option value="popular" {{ $sort == 'popular' ? 'selected' : '' }}>Popular</option>
                    <option value="answers" {{ $sort == 'answers' ? 'selected' : '' }}>Number of Answers</option>
                </select>
            </form>
        </div>
    </div>

    @include('partials.list_of_questions', ['questions' => $questions])

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <ul class="pagination pagination-sm">
            {{-- Previous Page Link --}}
            @if ($questions->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $questions->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($questions->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $questions->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($questions->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $questions->nextPageUrl() }}" rel="next">Next</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Next</span>
                </li>
            @endif
        </ul>
    </div>
</div>
@endsection