<!-- display in a row the upvote button with a circle with a arrow pointing up in the center, then below it in another row, it displays the question or answer score that we get from the view in the database, then in the row below that one it displays the downvote button that is a circle with a arrow pointing down in the center -->
<!-- these buttons change and have a fill applied to the specified depending if the user that is seeing the question or answer has upvoted that question or answer -->
<!-- the arguments that are passed are whether this elements refers to a question or an answer, the question or the answer that this element refers to, the score of the element and also if the user has a vote on the element and if so we need to fill icon to the button corresponding to the previously registered user vote  -->
<!-- it should use ajax so it doesn't reload the page everytime the user decides to vote on something -->

@props(['item', 'userVote', 'itemType'])

<div class="d-flex flex-column align-items-center">
    <!-- upvote Button -->
    <button class="btn btn-link p-0" onclick="toggleVote('{{ $itemType }}', '{{ $item->id }}', 'positive')">
        <i class="fas fa-arrow-up {{ $userVote == 'positive' ? 'text-primary' : 'text-secondary' }}"></i>
    </button>

    <!-- score -->
    <div id="vote-count-{{ $itemType }}-{{ $item->id }}">
        {{ $item->score->number_of_votes ?? 0 }}
    </div>

    <!-- downvote button -->
    <button class="btn btn-link p-0" onclick="toggleVote('{{ $itemType }}', '{{ $item->id }}', 'negative')">
        <i class="fas fa-arrow-down {{ $userVote == 'negative' ? 'text-primary' : 'text-secondary' }}"></i>
    </button>
</div>

<div id="vote-error-container" class="alert alert-danger position-fixed d-none" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999; width: 300px; text-align: center;">
    <button type="button" class="close" onclick="document.getElementById('vote-error-container').classList.add('d-none');">
        <span>&times;</span>
    </button>
    <span id="vote-error-message"></span>
</div>

<script>
    function handleVote(itemType, itemId, voteType) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/${itemType}s/${itemId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ vote_type: voteType })
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = "{{ route('login') }}";
            } else if (response.status === 403) {
                const errorBox = document.getElementById('vote-error-container');
                document.getElementById('vote-error-message').innerText = 'You cannot vote on your own question or answer.';
                errorBox.classList.remove('d-none');
                return null;
            } else if (response.ok) {
                return response.json();
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .then(data => {
            if (data) {
                // update the vote count
                document.getElementById(`vote-count-${itemType}-${itemId}`).innerText = data.newVoteCount;

                // update button styles
                upvote_button = document.querySelector(`button[onclick="toggleVote('${itemType}', '${itemId}', 'positive')"] i`);
                downvote_button = document.querySelector(`button[onclick="toggleVote('${itemType}', '${itemId}', 'negative')"] i`);

                upvote_button.classList.remove('text-primary', 'text-secondary');
                downvote_button.classList.remove('text-primary', 'text-secondary');

                if (data.userVote === 'positive') {
                    upvote_button.classList.add('text-primary');
                    downvote_button.classList.add('text-secondary');
                } else if (data.userVote === 'negative') {
                    upvote_button.classList.add('text-secondary');
                    downvote_button.classList.add('text-primary');
                } else {
                    upvote_button.classList.add('text-secondary');
                    downvote_button.classList.add('text-secondary');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function toggleVote(itemType, itemId, voteType) {
        upvote_button = document.querySelector(`button[onclick="toggleVote('${itemType}', '${itemId}', 'positive')"] i`);
        downvote_button = document.querySelector(`button[onclick="toggleVote('${itemType}', '${itemId}', 'negative')"] i`);

        if (voteType === 'positive' && downvote_button.classList.contains('text-primary')) {
            handleVote(itemType, itemId, 'negative'); // remove the negative vote
            handleVote(itemType, itemId, 'positive'); // register the positive vote
        } else if (voteType === 'negative' && upvote_button.classList.contains('text-primary')) {
            handleVote(itemType, itemId, 'positive'); // remove the positive vote
            handleVote(itemType, itemId, 'negative'); // register the negative vote
        } else {
            handleVote(itemType, itemId, voteType);
        }
    }
</script>