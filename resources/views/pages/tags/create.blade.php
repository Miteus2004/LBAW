@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Add New Tag</h1>

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

    <form action="{{ route('tags.store') }}" method="POST">
        @csrf

        <!-- Tag Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Tag Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">Add Tag</button>
    </form>
</div>
@endsection