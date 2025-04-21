@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Ask a New Question</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('questions.store') }}" method="POST">
        @csrf

        <!-- Question Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Question Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required>
        </div>

        <!-- Question Content -->
        <div class="mb-3">
            <label for="content" class="form-label">Question Details</label>
            <textarea name="content" class="form-control" id="content" rows="5" required>{{ old('content') }}</textarea>
        </div>

        <!-- Tags (Comma-Separated) -->
        <div class="mb-3">
            <label for="tags" class="form-label">Tags (comma-separated)</label>
            <input type="text" name="tags" class="form-control" id="tags" value="{{ old('tags') }}" placeholder="e.g., laravel, php, web-development">
            <div class="form-text">Add tags related to your question, separated by commas.</div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">
            <i class="fas fa-paper-plane mr-1"></i> Submit Question
        </button>
    </form>
</div>
@endsection
