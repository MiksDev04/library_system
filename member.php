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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "INSERT INTO members (Member_Name, Member_Email, Member_Phone_Number) VALUES ('$name', '$email', '$phone')";
    mysqli_query($conn, $query);
    header("Location: member.php"); // Redirect to avoid form resubmission
    
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $query = "UPDATE members SET Member_Name='$name', Member_Email='$email', Member_Phone_Number='$phone' WHERE Member_ID='$id'";
    mysqli_query($conn, $query);
    header("Location: member.php"); // Redirect to avoid form resubmission
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM members WHERE Member_ID='$id'";

    try {
        //code...
        mysqli_query($conn, $query);
        header("Location: member.php"); // Redirect to avoid form resubmission
    } catch (\Throwable $th) {
        //throw $th;
        echo "
        <div class=' position-fixed w-100 z-3 alert alert-danger alert-dismissible fade show' role='alert'>
          Cannot delete this member record as it is referenced in other records.
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";

    }

}

$members_result = mysqli_query($conn, "SELECT * FROM members");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members Management</title>
    <link href="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php
// Include header and navigation
include 'nav.php';
?>
    <div class="container mt-2">
        <h2>Members Management</h2>

        <!-- Button to Open Create Modal -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Add Member</button>

        <!-- Table to Display Members -->
        <table class="table mt-2 table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($members_result) > 0) : ?>
                    <?php while ($row = mysqli_fetch_assoc($members_result)) : ?>
                        <tr>
                            <td><?= $row['Member_ID'] ?></td>
                            <td><?= $row['Member_Name'] ?></td>
                            <td><?= $row['Member_Email'] ?></td>
                            <td><?= $row['Member_Phone_Number'] ?></td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['Member_ID'] ?>">Edit</button>
                                
                                <!-- Delete Button (opens the confirmation modal) -->
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['Member_ID'] ?>">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $row['Member_ID'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="member.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $row['Member_ID'] ?>">
                                    <input type="hidden" name="update" value="true">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel">Edit Member</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= $row['Member_Name'] ?>" required>
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= $row['Member_Email'] ?>" required>
                                            <label>Phone Number</label>
                                            <input type="number" name="phone" class="form-control" value="<?= $row['Member_Phone_Number'] ?>" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal<?= $row['Member_ID'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="" method="GET">
                                    <input type="hidden" name="delete" value="<?= $row['Member_ID'] ?>">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Delete Member</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete <strong><?= $row['Member_Name'] ?></strong>?</p>
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
                    <tr><td colspan="5" class="text-center">No records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="member.php" method="POST">
                <input type="hidden" name="create" value="true">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Add New Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <label>Phone Number</label>
                        <input type="number" name="phone" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Member</button>
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
