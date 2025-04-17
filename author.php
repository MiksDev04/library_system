<?php
// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "new_library"; // Replace with your actual database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}




// Fetch distinct nationalities for dropdown
$nationalities_result = mysqli_query($conn, "SELECT DISTINCT Author_Nationality FROM authors");

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
  $name = $_POST['name'];
  $nationality = $_POST['nationality'];
  $birthdate = $_POST['birthdate'];
  $sql = "INSERT INTO authors (Author_Name, Author_Nationality, Author_Birthdate) VALUES ('$name', '$nationality', '$birthdate')";
  mysqli_query($conn, $sql);
  header("Location: author.php");
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $nationality = $_POST['nationality'];
  $birthdate = $_POST['birthdate'];
  $sql = "UPDATE authors SET Author_Name='$name', Author_Nationality='$nationality', Author_Birthdate='$birthdate' WHERE Author_ID=$id";
  mysqli_query($conn, $sql);
  header("Location: author.php");
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
  $id = $_POST['id'];
  try {
    $sql = "DELETE FROM authors WHERE Author_ID=$id";
    mysqli_query($conn, $sql);
    header("Location: author.php");
  } catch (\Throwable $th) {
    // Handle foreign key constraint error

    echo "
    <div class=' position-fixed w-100 z-3 alert alert-danger alert-dismissible fade show mt-5' role='alert'>
      Cannot delete this author record as it is referenced in other records.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
  }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_nationality = isset($_GET['filter_nationality']) ? mysqli_real_escape_string($conn, $_GET['filter_nationality']) : '';

$where = [];

if (!empty($search)) {
  $where[] = "(Author_Name LIKE '%$search%' OR Author_Nationality LIKE '%$search%')";
}
if (!empty($filter_nationality)) {
  $where[] = "Author_Nationality = '$filter_nationality'";
}

$whereClause = '';
if (!empty($where)) {
  $whereClause = 'WHERE ' . implode(' AND ', $where);
}

$sql = "SELECT * FROM authors $whereClause";
$result = mysqli_query($conn, $sql);

// Fetch all authors
// $result = mysqli_query($conn, "SELECT * FROM authors");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Authors CRUD</title>
  <link href="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php
  // Include header and navigation
  include 'nav.php';
  ?>
  <div class="container mt-2">
    <h1 class="text-primary py-2 text-center">Authors Management</h2>

    <!-- Button trigger modal for Create -->
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add Author</button>

    <form method="GET" class="row g-2 align-items-center mb-3">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name or nationality" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-4">
        <select name="filter_nationality" class="form-select">
          <option value="">Filter by Nationality</option>
          <?php while ($nat = mysqli_fetch_assoc($nationalities_result)): ?>
            <option value="<?= htmlspecialchars($nat['Author_Nationality']) ?>" <?= $filter_nationality == $nat['Author_Nationality'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($nat['Author_Nationality']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary">Search / Filter</button>
        <a href="author.php" class="btn btn-outline-secondary">Clear</a>
      </div>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Nationality</th>
              <th>Birthdate</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $row['Author_ID'] ?></td>
                <td><?= $row['Author_Name'] ?></td>
                <td><?= $row['Author_Nationality'] ?></td>
                <td><?= $row['Author_Birthdate'] ?></td>
                <td>
                  <!-- Edit Button -->
                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['Author_ID'] ?>">Edit</button>
                  <!-- Delete Button -->
                  <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['Author_ID'] ?>">Delete</button>
                </td>
              </tr>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $row['Author_ID'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <form action="author.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['Author_ID'] ?>">
                    <input type="hidden" name="update" value="true">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Update Author</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $row['Author_Name'] ?>" required>
                        <label>Nationality</label>
                        <input type="text" name="nationality" class="form-control" value="<?= $row['Author_Nationality'] ?>" required>
                        <label>Birthdate</label>
                        <input type="date" name="birthdate" class="form-control" value="<?= $row['Author_Birthdate'] ?>" required>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Delete Modal -->
              <div class="modal fade" id="deleteModal<?= $row['Author_ID'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <form action="author.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['Author_ID'] ?>">
                    <input type="hidden" name="delete" value="true">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Delete Author</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Are you sure you want to delete <strong><?= $row['Author_Name'] ?></strong>?
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-muted">No records found.</p>
    <?php endif; ?>
  </div>

  <!-- Add Author Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form action="author.php" method="POST">
        <input type="hidden" name="create" value="true">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Author</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
            <label>Nationality</label>
            <input type="text" name="nationality" class="form-control" required>
            <label>Birthdate</label>
            <input type="date" name="birthdate" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Create</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </form>
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