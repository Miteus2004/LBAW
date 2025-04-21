<!-- calls vote.blade.php and passes the arguments accordingly and adds a bookmark button below it and checks if the user has already bookmarked the question, if he has then we need to fill the bookmark icon -->
<!-- this one should also use ajax so the page doesnt reload everytime the user puts a bookmark -->

@props(['item', 'userVote', 'bookmarked'])

@include('partials.vote', ['item' => $item, 'userVote' => $userVote, 'itemType' => 'question'])

<!-- Bookmark Button -->
<button class="btn btn-link p-0 mt-2" onclick="handleBookmark('{{ $item->id }}')">
    <i class="fas fa-bookmark {{ $bookmarked ? 'text-primary' : 'text-secondary' }}"></i>
</button>

<script>
    function handleBookmark(itemId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/questions/${itemId}/bookmark`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (response.status === 401 || response.status === 403) {
                window.location.href = "{{ route('login') }}";
            } else if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .then(data => {
            const bookmarkBtn = document.querySelector(`button[onclick="handleBookmark('${itemId}')"] i`);
            bookmarkBtn.classList.remove('text-primary', 'text-secondary');

            if (data.bookmarked) {
                bookmarkBtn.classList.add('text-primary');
            } else {
                bookmarkBtn.classList.add('text-secondary');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>