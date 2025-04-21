@extends('layouts.app')

@section('content')
<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-body">
            <!-- Username -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Username</strong></h6>
                </div>
                <div class="col-sm-9">
                    {{ $user->username }}
                </div>
            </div>
            <hr>
            
            <!-- Email -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Email</strong></h6>
                </div>
                <div class="col-sm-9">
                    {{ $user->email }}
                </div>
            </div>
            <hr>

            <!-- Bio -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Bio</strong></h6>
                </div>
                <div class="col-sm-9">
                    {{ $user->bio }}
                </div>
            </div>
            <hr>

            <!-- Profile Image -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Profile Image</strong></h6>
                </div>
                <div class="col-sm-9">
                    @if ($user->image_url)
                        <img src="{{ asset($user->image_url) }}" alt="Profile Image" width="150">
                    @else
                        <p>No profile image.</p>
                    @endif
                </div>
            </div>
            <hr>

            <!-- Role -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Role</strong></h6>
                </div>
                <div class="col-sm-9">
                    {{ ucfirst($user->user_role) }}
                </div>
            </div>
            <hr>

            <!-- Badges -->
            <div class="row">
                <div class="col-sm-3">
                    <h6><strong>Badges</strong></h6>
                </div>
                <div class="col-sm-9">
                    @if($user->badges->isEmpty())
                        <p>No badges.</p>
                    @else
                            @foreach($user->badges as $badge)
                                {{ $badge->badge_name }}
                            @endforeach
                    @endif
                </div>
            </div>
            <hr>

            <!-- Edit and Delete Account Buttons -->
            @can('update', $user)
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary mt-3">
                    <i class="fas fa-edit mr-1"></i> Edit Account
                </a>
            @endcan

            @can('delete', $user)
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger mt-3">
                        <i class="fas fa-trash-alt mr-1"></i> Delete Account
                    </button>
                </form>
            @endcan

            @can('viewQuestionsAnswers', $user)
                <a href="{{ route('users.questions', $user->id) }}" class="btn btn-success mt-3">
                    <i class="fas fa-eye mr-1"></i> View My Questions
                </a>
                <a href="{{ route('users.answers', $user->id) }}" class="btn btn-success mt-3">
                    <i class="fas fa-eye mr-1"></i> View My Answers
                </a>
            @endcan
        </div>
    </div>
</div>
@endsection