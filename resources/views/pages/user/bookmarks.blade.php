@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->username }}'s Bookmarks</h1>
    @if($bookmarks->isEmpty())
        <p>No bookmarks found.</p>
    @else
        <ul class="list-group mb-4">
            @foreach($bookmarks as $question)
                <li class="list-group-item">
                    <a href="{{ route('questions.show', $question->id) }}">
                        {{ $question->title }}
                    </a>
                    <small class="text-muted">by {{ $question->user->username }}</small>
                </li>
            @endforeach
        </ul>
        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            <ul class="pagination pagination-sm">
                {{-- Previous Page Link --}}
                @if ($bookmarks->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookmarks->previousPageUrl() }}" rel="prev">Previous</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($bookmarks->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $bookmarks->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($bookmarks->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $bookmarks->nextPageUrl() }}" rel="next">Next</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">Next</span>
                    </li>
                @endif
            </ul>
        </div>
    @endif
</div>
@endsection