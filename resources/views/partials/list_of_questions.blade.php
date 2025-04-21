@props(['questions'])

@foreach ($questions as $question)
    @include('partials.element_of_list_of_questions', ['question' => $question])
    <hr>
@endforeach