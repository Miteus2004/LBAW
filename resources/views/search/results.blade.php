@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Search Results for "{{ $query }}"</h2>

    @if($questions->count())
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
    @else
        <div class="alert alert-warning">
            No questions found matching your query.
        </div>
    @endif
</div>
@endsection