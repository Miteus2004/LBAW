@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>Top Questions</h1>
        <!-- Filter Form -->
        <form action="{{ route('questions.index_home') }}" method="GET">
            <label for="filter">Posted:</label>
            <select name="timeframe" id="filter" class="form-select stylish-select" onchange="this.form.submit()">
                <option value="" {{ request('timeframe') == '' ? 'selected' : '' }}>All Time</option>
                <option value="month" {{ request('timeframe') == 'month' ? 'selected' : '' }}>Last Month</option>
                <option value="week" {{ request('timeframe') == 'week' ? 'selected' : '' }}>Last Week</option>
                <option value="day" {{ request('timeframe') == 'day' ? 'selected' : '' }}>Last Day</option>
            </select>
        </form>
    </div>


    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Create New Question Button -->
    <div class="mb-4 text-end">
        <a href="{{ route(name: 'questions.create') }}" class="btn btn-primary">
            <i class="fas fa-question mr-1"></i> Ask a New Question
        </a>
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
