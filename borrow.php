<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new_library";  // Change to your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// CRUD Operations
if (isset($_POST['create'])) {
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];

    $query = "INSERT INTO borrowingrecords (Borrowing_Date, Borrowing_Purpose, Book_ID, Member_ID) 
              VALUES ('$date', '$purpose', '$book_id', '$member_id')";
    mysqli_query($conn, $query);
    header("Location: borrow.php"); // Redirect to avoid form resubmission

}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $purpose = $_POST['purpose'];
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];

    $query = "UPDATE borrowingrecords 
              SET Borrowing_Date='$date', Borrowing_Purpose='$purpose', Book_ID='$book_id', Member_ID='$member_id' 
              WHERE Borrowing_ID='$id'";
    mysqli_query($conn, $query);
    header("Location: borrow.php"); // Redirect to avoid form resubmission
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM borrowingrecords WHERE Borrowing_ID='$id'";
    try {
        //code...
        mysqli_query($conn, $query);
        header("Location: borrow.php"); // Redirect to avoid form resubmission
    } catch (\Throwable $th) {
        //throw $th;
        // Handle foreign key constraint error
        echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
    Cannot delete this borrowing record as it is referenced in other records.
    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
}

// Fetch records for borrowing, books, and members
$search_member = isset($_GET['search_member']) ? $_GET['search_member'] : '';
$search_book = isset($_GET['search_book']) ? $_GET['search_book'] : '';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';

$query = "SELECT br.*, b.Book_Title, m.Member_Name 
          FROM borrowingrecords br
          JOIN books b ON br.Book_ID = b.Book_ID
          JOIN members m ON br.Member_ID = m.Member_ID
          WHERE 1=1";

if (!empty($search_member)) {
    $search_member = mysqli_real_escape_string($conn, $search_member);
    $query .= " AND m.Member_Name LIKE '%$search_member%'";
}

if (!empty($search_book)) {
    $search_book = mysqli_real_escape_string($conn, $search_book);
    $query .= " AND b.Book_Title LIKE '%$search_book%'";
}

if (!empty($filter_date)) {
    $filter_date = mysqli_real_escape_string($conn, $filter_date);
    $query .= " AND br.Borrowing_Date = '$filter_date'";
}

$borrowing_result = mysqli_query($conn, $query);

$books_result = mysqli_query($conn, "SELECT * FROM books");
$members_result = mysqli_query($conn, "SELECT * FROM members");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Records Management</title>
    <link href="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <?php
    // Include header and navigation
    include 'nav.php';
    ?>
    <div class="container mt-2">
        <h1 class="text-primary py-2 text-center">Borrowing Records Management</h2>

        <!-- Button to Open Create Modal -->
        <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#createModal">Add Borrowing Record</button>

        <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="search_member" class="form-control" placeholder="Search Member Name" value="<?= isset($_GET['search_member']) ? $_GET['search_member'] : '' ?>">
    </div>
    <div class="col-md-3">
        <input type="text" name="search_book" class="form-control" placeholder="Search Book Title" value="<?= isset($_GET['search_book']) ? $_GET['search_book'] : '' ?>">
    </div>
    <div class="col-md-3">
        <input type="date" name="filter_date" class="form-control" value="<?= isset($_GET['filter_date']) ? $_GET['filter_date'] : '' ?>">
    </div>
    <div class="col-md-3 d-grid gap-2 d-md-flex">
        <button type="submit" class="btn btn-outline-primary">Search / Filter</button>
        <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn btn-outline-secondary">Clear</a>
    </div>
</form>


        <!-- Table to Display Borrowing Records -->
        <div class="table-responsive">
            <table class="table mt-2 table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Borrowing Date</th>
                        <th>Purpose</th>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($borrowing_result) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($borrowing_result)) : ?>
                            <tr>
                                <td><?= $row['Borrowing_ID'] ?></td>
                                <td><?= $row['Borrowing_Date'] ?></td>
                                <td><?= $row['Borrowing_Purpose'] ?></td>
                                <td><?= $row['Book_Title'] ?></td>
                                <td><?= $row['Member_Name'] ?></td>

                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['Borrowing_ID'] ?>">Edit</button>

                                    <!-- Delete Button (opens the confirmation modal) -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['Borrowing_ID'] ?>">Delete</button>
                                </td>
                            </tr>

                            <!-- Edit Modal for each record -->
                            <div class="modal fade" id="editModal<?= $row['Borrowing_ID'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="borrow.php" method="POST">
                                        <input type="hidden" name="update" value="true">
                                        <input type="hidden" name="id" value="<?= $row['Borrowing_ID'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel">Edit Borrowing Record</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label>Borrowing Date</label>
                                                <input type="date" name="date" class="form-control" value="<?= $row['Borrowing_Date'] ?>" required>
                                                <label>Purpose</label>
                                                <input type="text" name="purpose" class="form-control" value="<?= $row['Borrowing_Purpose'] ?>" required>
                                                <label>Book</label>
                                                <select name="book_id" class="form-select" required>
                                                    <?php
                                                    $books_result = mysqli_query($conn, "SELECT * FROM books");
                                                    while ($book = mysqli_fetch_assoc($books_result)) {
                                                        echo "<option value='" . $book['Book_ID'] . "' " . ($book['Book_ID'] == $row['Book_ID'] ? 'selected' : '') . ">" . $book['Book_Title'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label>Member</label>
                                                <select name="member_id" class="form-select" required>
                                                    <?php
                                                    $members_result = mysqli_query($conn, "SELECT * FROM members");
                                                    while ($member = mysqli_fetch_assoc($members_result)) {
                                                        echo "<option value='" . $member['Member_ID'] . "' " . ($member['Member_ID'] == $row['Member_ID'] ? 'selected' : '') . ">" . $member['Member_Name'] . "</option>";
                                                    }
                                                    ?>
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

                            <!-- Delete Modal for each record -->
                            <div class="modal fade" id="deleteModal<?= $row['Borrowing_ID'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="borrow.php" method="GET">
                                        <input type="hidden" name="delete" value="<?= $row['Borrowing_ID'] ?>">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel">Delete Borrowing Record</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this borrowing record?
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
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="borrow.php" method="POST">
                <input type="hidden" name="create" value="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Add New Borrowing Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Borrowing Date</label>
                        <input type="date" name="date" class="form-control" required>
                        <label>Purpose</label>
                        <input type="text" name="purpose" class="form-control" required>
                        <label>Book</label>
                        <select name="book_id" class="form-select" required>
                            <option value="" disabled selected>Select Book</option>
                            <?php
                            $books_result = mysqli_query($conn, "SELECT * FROM books");
                            while ($book = mysqli_fetch_assoc($books_result)): ?>
                                <option value="<?= $book['Book_ID'] ?>"><?= $book['Book_Title'] ?></option>
                            <?php endwhile; ?>
                        </select>

                        <label>Member</label>
                        <select name="member_id" class="form-select" required>
                            <option value="" disabled selected>Select Member</option>
                            <?php
                            $members_result = mysqli_query($conn, "SELECT * FROM members");
                            while ($member = mysqli_fetch_assoc($members_result)): ?>
                                <option value="<?= $member['Member_ID'] ?>"><?= $member['Member_Name'] ?></option>
                            <?php endwhile; ?>
                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Record</button>
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