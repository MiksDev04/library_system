<!-- Responsive & Aesthetic Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php">
      📚 Library System
    </a>
    
    <!-- Toggler for small screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible nav items -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto gap-2">
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3 rounded-pill hover-bg" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3 rounded-pill hover-bg" href="author.php">Authors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3 rounded-pill hover-bg" href="book.php">Books</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3 rounded-pill hover-bg" href="member.php">Members</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-dark fw-semibold px-3 rounded-pill hover-bg" href="borrow.php">Borrowing</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Optional: Custom hover background style -->
<style>
  .hover-bg:hover {
    background-color: #f0f0f0;
    transition: 0.3s;
  }
</style>
