<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --footer-bg: #2c3e50;
      --footer-text: #ecf0f1;
      --footer-accent: #3498db;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .content-wrapper {
      flex: 1;
      padding-bottom: 60px;
      /* Space for footer */
    }

    footer {
      background: linear-gradient(135deg, var(--footer-bg) 0%, #1a252f 100%);
      color: var(--footer-text);
      padding: 2.5rem 0;
      margin-top: auto;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .footer-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .footer-logo {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: white;
    }

    .footer-logo span {
      color: var(--footer-accent);
    }

    .footer-links {
      display: flex;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
      justify-content: center;
    }

    .footer-link {
      color: var(--footer-text);
      text-decoration: none;
      transition: color 0.3s ease;
      font-size: 0.9rem;
    }

    .footer-link:hover {
      color: var(--footer-accent);
      text-decoration: none;
    }

    .footer-social {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .social-icon {
      color: var(--footer-text);
      font-size: 1.2rem;
      transition: transform 0.3s ease, color 0.3s ease;
    }

    .social-icon:hover {
      color: var(--footer-accent);
      transform: translateY(-3px);
    }

    .footer-copyright {
      font-size: 0.85rem;
      opacity: 0.8;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      width: 100%;
    }

    /* Animation for the footer */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    footer {
      animation: fadeIn 0.6s ease-out forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .footer-links {
        flex-direction: column;
        gap: 0.8rem;
      }

      .footer-content {
        padding: 0 15px;
      }
    }
  </style>
</head>

<body>


  <!-- Enhanced Footer -->
  <footer class=" mt-5">
    <div class="footer-container">
      <div class="footer-content">
        <div class="footer-logo">
          <i class="bi bi-book-half"></i> Library<span>System</span>
        </div>

        <div class="footer-links">
          <a href="home.php" class="footer-link">Home</a>
          <a href="book.php" class="footer-link">Books</a>
          <a href="author.php" class="footer-link">Authors</a>
          <a href="member.php" class="footer-link">Members</a>
          <a href="borrow.php" class="footer-link">Borrowing</a>
        </div>

        <div class="footer-social">
          <a href="#" class="social-icon" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
              <path d="M8.94 6.75H10V5H8.94C8.14 5 8 5.34 8 6v1H7v2h1v5h2V9h1.438l.154-2H10V6.05c0-.296.064-.3.3-.3z" />
            </svg>
          </a>

          <a href="#" class="social-icon" aria-label="Twitter">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
              <path d="M5.026 15c6.038 0 9.341-5 9.341-9.334 0-.14 0-.282-.01-.422A6.676 6.676 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518A3.301 3.301 0 0 0 15.555 2.1a6.533 6.533 0 0 1-2.084.794A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.766-3.429A3.289 3.289 0 0 0 2.22 6.13a3.267 3.267 0 0 1-1.487-.41v.041a3.284 3.284 0 0 0 2.633 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.615-.059 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
            </svg>
          </a>

          <a href="#" class="social-icon" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
              <path d="M8 3c1.636 0 1.834.006 2.477.035.636.03 1.037.138 1.28.23.27.102.46.223.66.423.2.2.321.39.423.66.092.243.2.644.23 1.28.029.643.035.841.035 2.477s-.006 1.834-.035 2.477c-.03.636-.138 1.037-.23 1.28a1.77 1.77 0 0 1-.423.66 1.77 1.77 0 0 1-.66.423c-.243.092-.644.2-1.28.23-.643.029-.841.035-2.477.035s-1.834-.006-2.477-.035c-.636-.03-1.037-.138-1.28-.23a1.77 1.77 0 0 1-.66-.423 1.77 1.77 0 0 1-.423-.66c-.092-.243-.2-.644-.23-1.28C3.006 9.834 3 9.636 3 8s.006-1.834.035-2.477c.03-.636.138-1.037.23-1.28.102-.27.223-.46.423-.66.2-.2.39-.321.66-.423.243-.092.644-.2 1.28-.23C6.166 3.006 6.364 3 8 3zm0-1.5C6.326 1.5 6.107 1.507 5.464 1.536c-.648.03-1.095.14-1.477.29a3.281 3.281 0 0 0-1.189.777A3.281 3.281 0 0 0 2.02 3.493c-.15.382-.26.829-.29 1.477C1.5 6.107 1.5 6.326 1.5 8s.007 1.893.036 2.536c.03.648.14 1.095.29 1.477.162.41.382.771.777 1.189.418.395.78.615 1.189.777.382.15.829.26 1.477.29C6.107 14.5 6.326 14.5 8 14.5s1.893-.007 2.536-.036c.648-.03 1.095-.14 1.477-.29a3.281 3.281 0 0 0 1.189-.777 3.281 3.281 0 0 0 .777-1.189c.15-.382.26-.829.29-1.477.029-.643.036-.862.036-2.536s-.007-1.893-.036-2.536c-.03-.648-.14-1.095-.29-1.477a3.281 3.281 0 0 0-.777-1.189 3.281 3.281 0 0 0-1.189-.777c-.382-.15-.829-.26-1.477-.29C9.893 1.5 9.674 1.5 8 1.5z" />
              <path d="M8 5.5A2.5 2.5 0 1 0 8 10a2.5 2.5 0 0 0 0-4.5zm0 1.5A1 1 0 1 1 8 9a1 1 0 0 1 0-2zM11.5 4a.5.5 0 1 0 0 1 .5.5 0 0 0 0-1z" />
            </svg>
          </a>

          <a href="#" class="social-icon" aria-label="LinkedIn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
              <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.214c.837 0 1.358-.554 1.358-1.248-.015-.71-.521-1.248-1.342-1.248-.82 0-1.358.538-1.358 1.248 0 .694.521 1.248 1.327 1.248h.015zM13.458 13.394v-3.986c0-2.128-1.136-3.117-2.648-3.117-1.222 0-1.77.672-2.078 1.145v-1.007H6.331c.03.664 0 7.225 0 7.225h2.401V9.46c0-.215.015-.43.08-.586.175-.43.574-.875 1.243-.875.876 0 1.226.66 1.226 1.63v3.765h2.177z" />
            </svg>
          </a>

        </div>

        <div class="footer-copyright">
          &copy; <?= date("Y") ?> Library Management System. All rights reserved.
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>