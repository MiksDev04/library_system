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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Dashboard</title>
  <link href="./bootstrap-5.3.3-dist/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #3f37c9;
      --accent-color: #4895ef;
      --light-color: #f8f9fa;
      --dark-color: #212529;
      --success-color: #4cc9f0;
      --info-color: #4895ef;
      --warning-color: #f8961e;
      --danger-color: #f72585;
    }
    
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--dark-color);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
    }
    
    .dashboard-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 2rem 0;
      margin-bottom: 2rem;
      border-radius: 0 0 1rem 1rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .card {
      border: none;
      border-radius: 0.75rem;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      overflow: hidden;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }
    
    .card-icon {
      width: 50px;
      height: 50px;
      margin: 0 auto 1rem;
      display: block;
    }
    
    .stat-card {
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    /* .stat-card::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
      transform: rotate(30deg);
    } */
    
    /* .stat-card:hover {
      animation: shine 1.5s;
    } */
    
    /* @keyframes shine {
      0% { transform: rotate(30deg) translate(-10%, -10%); }
      100% { transform: rotate(30deg) translate(10%, 10%); }
    } */
    
    .authors-card {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }
    
    .books-card {
      background: linear-gradient(135deg, var(--success-color), var(--info-color));
    }
    
    .members-card {
      background: linear-gradient(135deg, var(--warning-color), #f3722c);
    }
    
    .borrowing-card {
      background: linear-gradient(135deg, var(--danger-color), #b5179e);
    }
    
    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0.5rem 0;
    }
    
    .stat-label {
      font-size: 1rem;
      opacity: 0.9;
      letter-spacing: 0.5px;
    }
    
    footer {
      background: linear-gradient(135deg, var(--dark-color), #343a40);
      color: white;
      padding: 2rem 0;
      margin-top: 3rem;
    }
    
    .footer-links a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      margin: 0 0.5rem;
      transition: color 0.3s;
    }
    
    .footer-links a:hover {
      color: white;
    }
    
    /* SVG icon styles */
    .svg-icon {
      width: 24px;
      height: 24px;
      vertical-align: middle;
      margin-right: 5px;
      fill: currentColor;
    }
    
    .section-icon {
      width: 20px;
      height: 20px;
      margin-right: 8px;
      vertical-align: text-top;
      fill: currentColor;
    }
  </style>
</head>
<body>

<?php
// Include header and navigation
include 'nav.php';
?>

<div class="dashboard-header">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="display-4 fw-bold">Library Dashboard</h1>
        <p class="lead">Welcome to your library management system</p>
      </div>
      <div class="col-md-4 text-md-end">
        <span class="badge bg-light text-dark p-2">
          <svg class="svg-icon" viewBox="0 0 448 512">
            <path d="M152 64H296V24C296 10.75 306.7 0 320 0C333.3 0 344 10.75 344 24V64H384C419.3 64 448 92.65 448 128V448C448 483.3 419.3 512 384 512H64C28.65 512 0 483.3 0 448V128C0 92.65 28.65 64 64 64H104V24C104 10.75 114.7 0 128 0C141.3 0 152 10.75 152 24V64zM48 448C48 456.8 55.16 464 64 464H384C392.8 464 400 456.8 400 448V192H48V448z"/>
          </svg>
          <?php echo date('F j, Y'); ?>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card authors-card text-center p-4">
        <svg class="card-icon" viewBox="0 0 448 512">
          <path fill="white" d="M224 256A128 128 0 1 0 96 128a128 128 0 0 0 128 128zm89.6 32h-11.7a174.8 174.8 0 0 1-155.8 0H134.4A134.4 134.4 0 0 0 0 422.4V464a48 48 0 0 0 48 48H400a48 48 0 0 0 48-48v-41.6A134.4 134.4 0 0 0 313.6 288z"/>
        </svg>
        <h2 class="stat-number"><?= $authors_count ?></h2>
        <p class="stat-label">AUTHORS</p>
        <div class="mt-3">
          <a href="author.php" class="text-white" >View Details 
            <svg style="width:14px;height:14px;fill:white;vertical-align:middle" viewBox="0 0 448 512">
              <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card books-card text-center p-4">
        <svg class="card-icon" viewBox="0 0 448 512">
          <path fill="white" d="M96 96V64c0-17.7 14.3-32 32-32H320c17.7 0 32 14.3 32 32V96h32c17.7 0 32 14.3 32 32V448c0 17.7-14.3 32-32 32H64c-17.7 0-32-14.3-32-32V128c0-17.7 14.3-32 32-32H96zm64-32v32H288V64H160z"/>
        </svg>
        <h2 class="stat-number"><?= $books_count ?></h2>
        <p class="stat-label">BOOKS</p>
        <div class="mt-3">
          <a href="book.php" class="text-white">View Details 
            <svg style="width:14px;height:14px;fill:white;vertical-align:middle" viewBox="0 0 448 512">
              <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card members-card text-center p-4">
        <svg class="card-icon" viewBox="0 0 640 512">
          <path fill="white" d="M96 96a64 64 0 1 1 0 128 64 64 0 1 1 0-128zM0 416c0-70.7 57.3-128 128-128h64c70.7 0 128 57.3 128 128v32H0V416zM480 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm32 32c-35.3 0-66.9 15.5-88.4 40H560c8.8 0 16 7.2 16 16v32H320v-32c0-53-43-96-96-96H128c-53 0-96 43-96 96v32H0v-32C0 316.3 57.3 256 128 256h64c70.7 0 128 57.3 128 128v32h192v-32c0-17.7-14.3-32-32-32H400.4c21.5-24.5 53.1-40 88.4-40z"/>
        </svg>
        <h2 class="stat-number"><?= $members_count ?></h2>
        <p class="stat-label">MEMBERS</p>
        <div class="mt-3">
          <a href="member.php" class="text-white">View Details 
            <svg style="width:14px;height:14px;fill:white;vertical-align:middle" viewBox="0 0 448 512">
              <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card borrowing-card text-center p-4">
        <svg class="card-icon" viewBox="0 0 384 512">
          <path fill="white" d="M32 32C14.3 32 0 46.3 0 64s14.3 32 32 32V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V96c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM128 160h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 96h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 96h128c8.8 0 16 7.2 16 16s-7.2 16-16 16H128c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/>
        </svg>
        <h2 class="stat-number"><?= $borrowing_count ?></h2>
        <p class="stat-label">BORROWINGS</p>
        <div class="mt-3">
          <a href="borrow.php" class="text-white">View Details 
            <svg style="width:14px;height:14px;fill:white;vertical-align:middle" viewBox="0 0 448 512">
              <path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
            </svg>
          </a>
        </div>
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
<script>
  // Simple animation for cards when page loads
  document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }, 100 * index);
    });
  });
</script>
</body>
</html>