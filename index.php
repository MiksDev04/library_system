<?php
$conn = mysqli_connect("localhost", "root", "", "new_library");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$authors_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM authors"))['total'];
$books_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM books"))['total'];
$members_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM members"))['total'];
$borrowing_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM borrowingrecords"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f4f4;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .icon svg {
      width: 50px;
      height: 50px;
      margin-bottom: 10px;
      fill: white;
    }
    footer {
      background: #343a40;
      color: white;
      text-align: center;
      padding: 1rem 0;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<?php
// Include header and navigation
include 'nav.php';
?>

<div class="container py-5">
  <h2 class="mb-4 text-center">Dashboard Overview</h2>
  <div class="row text-white">
    <div class="col-md-3 mb-4">
      <div class="card bg-primary text-center p-3">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 96 128a128 128 0 0 0 128 128zm89.6 32h-11.7a174.8 174.8 0 0 1-155.8 0H134.4A134.4 134.4 0 0 0 0 422.4V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48v-41.6A134.4 134.4 0 0 0 313.6 288z"/></svg>
        </div>
        <h5>Authors</h5>
        <h2><?= $authors_count ?></h2>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-success text-center p-3">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M96 96V64c0-17.7 14.3-32 32-32H320c17.7 0 32 14.3 32 32V96h32c17.7 0 32 14.3 32 32V448c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V128c0-17.7 14.3-32 32-32H96zm64-32v32H288V64H160z"/></svg>
        </div>
        <h5>Books</h5>
        <h2><?= $books_count ?></h2>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-warning text-center p-3">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M96 96a64 64 0 1 1 0 128 64 64 0 1 1 0-128zM0 416c0-70.7 57.3-128 128-128h64c70.7 0 128 57.3 128 128v32H0V416zM480 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm32 32c-35.3 0-66.9 15.5-88.4 40H560c8.8 0 16 7.2 16 16v32H320v-32c0-53-43-96-96-96H128c-53 0-96 43-96 96v32H0v-32C0 316.3 57.3 256 128 256h64c70.7 0 128 57.3 128 128v32h192v-32c0-17.7-14.3-32-32-32H400.4c21.5-24.5 53.1-40 88.4-40z"/></svg>
        </div>
        <h5>Members</h5>
        <h2><?= $members_count ?></h2>
      </div>
    </div>
    <div class="col-md-3 mb-4">
      <div class="card bg-danger text-center p-3">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path d="M32 32C14.3 32 0 46.3 0 64s14.3 32 32 32V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V96c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM128 160h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 96h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 96h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
        </div>
        <h5>Borrowing</h5>
        <h2><?= $borrowing_count ?></h2>
      </div>
    </div>
  </div>
</div>

<?php
// Include footer
include 'footer.php';
?>
<?php
mysqli_close($conn);

?>
<script src="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
