@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Profile</h2>
    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        
        <!-- Bio -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 d-flex row align-content-center">
                        <h6 class="mb-0 profiledata"><strong>Bio</strong></h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <textarea class="form-control" name="bio" id="bio" rows="3">{{ $user->bio }}</textarea>
                    </div>
                </div>
                <hr>
                
                <!-- Profile Image -->
                <div class="row">
                    <div class="col-sm-3 d-flex row align-content-center">
                        <h6 class="mb-0 profiledata"><strong>Profile Image</strong></h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="file" class="form-control-file" name="image" id="image">
                    </div>
                </div>
                <hr>
                
                <!-- Save Button -->
                <div class="row">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save mr-1"></i> Update
                        </button>
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-danger" role="button">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection