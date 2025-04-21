<!-- displays a list of buttons with the name of each tag they refer to and redirects to the tag when the button is pressed -->
<!-- takes a list of tags has the argument -->

@props(['tags'])

@foreach($tags as $tag)
    <a href="{{ route('tags.show', $tag->id) }}" class="badge bg-light text-decoration-none me-1">
        {{ $tag->name }}
    </a>
@endforeach