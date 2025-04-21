<!-- Sidebar -->
<nav class="nav flex-column d-none d-lg-flex" id="sidebar" style="position: fixed; top: 70px; z-index: 9999; background-color: #f8f9fa;">
    <a class="nav-link d-flex align-items-center mb-2" href="{{ url('/') }}">
        <span class="material-symbols-outlined me-2">home</span> Home
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('questions.index') }}">
        <span class="material-symbols-outlined me-2">question_answer</span> Questions
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ Auth::check() ? route('users.show.bookmarks', Auth::id()) : route('login') }}">
        <span class="material-symbols-outlined me-2">bookmark</span> Bookmarks
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('tags.index') }}">
        <span class="material-symbols-outlined me-2">label</span> Tags
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('users.index') }}">
        <span class="material-symbols-outlined me-2">person</span> Users
    </a>
    <!-- <hr style="border-top: 1px solid black; width: calc(100% + 5rem); margin-left: 10;"> -->
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('faq', Auth::id()) }}">
        <span class="material-symbols-outlined me-2">help</span> FAQ
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('aboutUs', Auth::id()) }}">
        <span class="material-symbols-outlined me-2">info</span> About Us
    </a>
    <a class="nav-link d-flex align-items-center mb-2" href="{{ route('contactUs', Auth::id()) }}">
        <span class="material-symbols-outlined me-2">mail</span> Contact Us
    </a>
</nav>

<style>
@media (max-width: 992px) {
  #sidebar {
    top: 120px !important; /* makes it so all the buttons are slightly lower on small screens, because the top bar expands and would cover the home button */
    background-color: #f8f9fa !important; /* makes the sidebar have a background when it's toggled on so that the buttons don't go directly over the contents of the page in small screens */
  }
}
</style>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function() {
  var sidebar = document.getElementById('sidebar');
  // toggle sidebar
  sidebar.classList.toggle('d-none');
});
</script>
