@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">All Tags</h1>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        @foreach($tags as $tag)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $tag->name }}</h5>
                        <a href="{{ route('tags.show', $tag->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye mr-1"></i> View Tag
                        </a>
                        <p class="mt-2">{{ $tag->questions_count }} Questions</p>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Add New Tag (For Admins) -->
        @can('createTag', App\Models\Tag::class)
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Add New Tag</h5>
                        <a href="{{ route('tags.create') }}" class="btn btn-success">
                            <i class="fas fa-plus" style="font-size: 3rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        <ul class="pagination pagination-sm">
            {{-- Previous Page Link --}}
            @if ($tags->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $tags->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($tags->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $tags->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($tags->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $tags->nextPageUrl() }}" rel="next">Next</a>
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