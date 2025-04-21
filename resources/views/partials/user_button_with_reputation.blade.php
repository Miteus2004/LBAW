<!-- user button, receives the user that the button refers to, it displays the username and the user's reputation (the user total score that is stored inside the view in the database), that when hovered changes color and that when pressed goes to that user's profile -->
@props(['user'])

<a href="{{ route('users.show', $user->id) }}" class="btn btn-link d-inline-flex align-items-center text-decoration-none p-0">
    <span>{{ $user->username }}</span>
    <span class="badge text-warning mt-1 ms-2">
        <i class="fas fa-star me-1"></i> {{ $user->reputation }}
    </span>
</a>
