@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>{{ $tag->name }}</h1>
        <div class="d-flex">
            @auth
                @if(Auth::user()->follow_tags->contains($tag->id))
                    <form action="{{ route('tags.unfollow', $tag->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary me-2">
                            <i class="fas fa-minus-circle"></i> Unfollow Tag
                        </button>
                    </form>
                @else
                    <form action="{{ route('tags.follow', $tag->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-plus-circle"></i> Follow Tag
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus-circle"></i> Follow Tag
                </a>
            @endauth

            @can('delete', $tag)
                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this tag? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger ml-3">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
            @endcan
        </div>
    </div>
    
    <div class="row">
        @foreach($questions as $question)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('questions.show', $question->id) }}">{{ $question->title }}</a>
                        </h5>
                        <p class="card-text">{{ Str::limit($question->content, 150) }}</p>
                        <p class="card-text text-muted">
                            Posted by <span style="position: relative; top: -2px;"> @include('partials.user_button_with_reputation', ['user' => $question->user]) </span> on {{ $question->posted->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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