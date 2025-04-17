<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "new_library"; // Replace with your actual database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $isbn = $_POST['isbn'];
    $published_year = $_POST['published_year'];
    $author_id = $_POST['author_id'];
    $sql = "INSERT INTO books (Book_Title, Book_Genre, Book_ISBN, Book_Published_Year, Author_ID) VALUES ('$title', '$genre', '$isbn', '$published_year', '$author_id')";
    mysqli_query($conn, $sql);
    header("Location: book.php");
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $isbn = $_POST['isbn'];
    $published_year = $_POST['published_year'];
    $author_id = $_POST['author_id'];
    $sql = "UPDATE books SET Book_Title='$title', Book_Genre='$genre', Book_ISBN='$isbn', Book_Published_Year='$published_year', Author_ID='$author_id' WHERE Book_ID=$id";
    mysqli_query($conn, $sql);
    header("Location: book.php");
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM books WHERE Book_ID=$id";
    try {
        //code...
        mysqli_query($conn, $sql);
        header("Location: book.php");
    } catch (\Throwable $th) {
        //throw $th;
        // Handle foreign key constraint error
        echo "
        <div class=' position-fixed w-100 z-3 alert alert-danger alert-dismissible fade show mt-5' role='alert'>
          Cannot delete this book record as it is referenced in other records.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$genre_filter = isset($_GET['genre_filter']) ? mysqli_real_escape_string($conn, $_GET['genre_filter']) : '';

$query = "SELECT * FROM books WHERE 1";

if (!empty($search)) {
    $query .= " AND (Book_Title LIKE '%$search%' OR Book_ISBN LIKE '%$search%' OR Book_Genre LIKE '%$search%')";
}

if (!empty($genre_filter)) {
    $query .= " AND Book_Genre = '$genre_filter'";
}


$result = mysqli_query($conn, $query);

$authors_result = mysqli_query($conn, "SELECT * FROM authors");
// Fetch all authors for the dropdown in the Add and Edit modals
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Books CRUD</title>
    <link href="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php
    // Include header and navigation
    include 'nav.php';
    ?>
    <div class="container mt-2">
        <h1 class="text-primary py-2 text-center">Books Management</h2>

        <!-- Button trigger modal for Create -->
        <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal">Add Book</button>

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by title, genre, ISBN..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            </div>
            <div class="col-md-3">
                <select name="genre_filter" class="form-select">
                    <option value="">All Genres</option>
                    <?php
                    $genres = json_decode(file_get_contents("genres.json"), true);
                    foreach ($genres as $genre) {
                        $selected = (isset($_GET['genre_filter']) && $_GET['genre_filter'] === $genre) ? 'selected' : '';
                        echo "<option value=\"$genre\" $selected>$genre</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary">Search / Filter</button>
                <a href="book.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>ISBN</th>
                            <th>Published Year</th>
                            <th>Author</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $row['Book_ID'] ?></td>
                                <td><?= $row['Book_Title'] ?></td>
                                <td><?= $row['Book_Genre'] ?></td>
                                <td><?= $row['Book_ISBN'] ?></td>
                                <td><?= $row['Book_Published_Year'] ?></td>
                                <td>
                                    <?php
                                    $author_id = $row['Author_ID'];
                                    $author_query = mysqli_query($conn, "SELECT * FROM authors WHERE Author_ID = $author_id");
                                    $author = mysqli_fetch_assoc($author_query);
                                    echo $author['Author_Name'];
                                    ?>
                                </td>
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['Book_ID'] ?>">Edit</button>
                                    <!-- Delete Button -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['Book_ID'] ?>">Delete</button>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <?php
                            // Fetch authors before looping through books to prevent overwriting the query
                            $authors_result = mysqli_query($conn, "SELECT * FROM authors");
                            ?>
                            <div class="modal fade" id="editModal<?= $row['Book_ID'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="book.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['Book_ID'] ?>">
                                        <input type="hidden" name="update" value="true">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Book</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Title</label>
                                                <input type="text" name="title" class="form-control" value="<?= $row['Book_Title'] ?>" required>
                                                <label>Genre</label>
                                                <select name="genre" class="form-select" required>
                                                    <?php
                                                    $genres = json_decode(file_get_contents('genres.json'), true);
                                                    foreach ($genres as $genre) {
                                                        $selected = ($genre == $row['Book_Genre']) ? 'selected' : '';
                                                        echo "<option value=\"$genre\" $selected>$genre</option>";
                                                    }
                                                    ?>
                                                </select>

                                                <label>ISBN</label>
                                                <input type="text" name="isbn" class="form-control" value="<?= $row['Book_ISBN'] ?>" required>
                                                <label>Published Year</label>
                                                <input type="number" name="published_year" class="form-control" value="<?= $row['Book_Published_Year'] ?>" required>

                                                <!-- Dropdown for Author -->
                                                <label>Author</label>
                                                <select name="author_id" class="form-select" required>
                                                    <?php while ($author = mysqli_fetch_assoc($authors_result)): ?>
                                                        <option value="<?= $author['Author_ID'] ?>" <?= ($author['Author_ID'] == $row['Author_ID']) ? 'selected' : '' ?>>
                                                            <?= $author['Author_Name'] ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
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
                            <div class="modal fade" id="deleteModal<?= $row['Book_ID'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="book.php" method="POST">
                                        <input type="hidden" name="id" value="<?= $row['Book_ID'] ?>">
                                        <input type="hidden" name="delete" value="true">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Book</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <strong><?= $row['Book_Title'] ?></strong>?
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

    <!-- Add Book Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="book.php" method="POST">
                <input type="hidden" name="create" value="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                        <label>Genre</label>
                        <select name="genre" class="form-select" required>
                            <?php
                            $genres = json_decode(file_get_contents("genres.json"), true);
                            if (!empty($genres)) {
                                foreach ($genres as $genre) {
                                    echo "<option value=\"$genre\">$genre</option>";
                                }
                            }
                            ?>
                        </select>

                        <label>ISBN</label>
                        <input type="text" name="isbn" class="form-control" required>
                        <label>Published Year</label>
                        <input type="number" name="published_year" class="form-control" required>
                        <!-- Dropdown for Author -->
                        <label>Author</label>
                        <select name="author_id" class="form-select" required>
                            <option value="" disabled selected>Select Author</option>
                            <?php
                            $authors_result = mysqli_query($conn, "SELECT * FROM authors");
                            while ($author = mysqli_fetch_assoc($authors_result)): ?>
                                <option value="<?= $author['Author_ID'] ?>"><?= $author['Author_Name'] ?></option>
                            <?php endwhile; ?>
                        </select>
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