@extends('layouts.app')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h1>My feed</h1>
    </div>

    <!-- Filter Form -->
    @if($followedTags->isNotEmpty())
        <form action="{{ route('questions.index_home') }}" method="GET" class="d-flex flex-column w-100">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="mt-3 w-75">
                    <label for="tags">Filter which tags to show:</label>
                    <div class="d-flex flex-wrap">
                        @foreach($followedTags->unique('id') as $tag)
                            @php
                                $isChecked = in_array($tag->id, $selectedTags);
                            @endphp
                            <button type="button" class="btn {{ $isChecked ? 'btn-primary' : 'btn-outline-secondary' }} mr-3 me-3 mb-2 d-flex align-items-center position-relative cursor-pointer" onclick="toggleButton(this)">
                                <i class="{{ $isChecked ? 'fas fa-check-square' : 'far fa-square' }} me-2"></i>
                                <span class="ml-2">{{ $tag->name }}</span>
                                <input class="btn-check d-none" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag{{ $tag->id }}" {{ $isChecked ? 'checked' : '' }}>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex align-items-center mt-3">
                    <label for="filter" class="me-4 mb-0" style="margin-right: 4px;">Posted:</label>
                    <select name="timeframe" id="filter" class="form-select stylish-select ms-1" onchange="this.form.submit()">
                        <option value="" {{ request('timeframe') == '' ? 'selected' : '' }}>All Time</option>
                        <option value="month" {{ request('timeframe') == 'month' ? 'selected' : '' }}>Last Month</option>
                        <option value="week" {{ request('timeframe') == 'week' ? 'selected' : '' }}>Last Week</option>
                        <option value="day" {{ request('timeframe') == 'day' ? 'selected' : '' }}>Last Day</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-start mt-3">
                <button type="submit" class="btn btn-success btn-md">
                    <i class="fas fa-filter me-1"></i> Apply Tags Filter
                </button>
            </div>
        </form>
    @endif

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Create New Question Button -->
    <div class="mb-4 mt-3 text-end">
        <a href="{{ route('questions.create') }}" class="btn btn-primary">
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

    <script>
        function toggleButton(button) {
            const checkbox = button.querySelector('.btn-check');
            const icon = button.querySelector('i');
            checkbox.checked = !checkbox.checked;
            if (checkbox.checked) {
                button.classList.remove('btn-outline-secondary');
                button.classList.add('btn-primary');
                icon.classList.remove('far', 'fa-square');
                icon.classList.add('fas', 'fa-check-square');
            } else {
                button.classList.remove('btn-primary');
                button.classList.add('btn-outline-secondary');
                icon.classList.remove('fas', 'fa-check-square');
                icon.classList.add('far', 'fa-square');
            }
        }

        // Initialize buttons based on their checkbox state
        document.querySelectorAll('.btn-check').forEach(checkbox => {
            const button = checkbox.closest('button');
            const icon = button.querySelector('i');
            if (checkbox.checked) {
                button.classList.add('btn-primary');
                icon.classList.add('fas', 'fa-check-square');
                icon.classList.remove('far', 'fa-square');
            } else {
                button.classList.add('btn-outline-secondary');
                icon.classList.add('far', 'fa-square');
                icon.classList.remove('fas', 'fa-check-square');
            }
        });
    </script>

</div>

@endsection
