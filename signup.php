<?php
// signup.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up & Login - Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background: url('signup.jpg') no-repeat center center fixed;
      background-size: cover;
      position: relative;
    }
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0, 0, 139, 0.8);
      z-index: -1;
    }
    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 1000;
      transition: background-color 0.3s;
    }
    header.scrolled {
      background-color: rgba(0, 0, 0, 0.9);
    }
    .navbar-nav .nav-link {
      color: #fff !important;
    }
    .main-content {
      margin-top: 80px;
      padding-bottom: 50px;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: transform 0.5s ease-in-out;
    }
    .card:hover {
      transform: scale(1.02);
    }
    .form-control, .form-select {
      border-radius: 5px;
    }
    .toggle-link {
      cursor: pointer;
      color: #007bff;
    }
    .dual-form { display: flex; gap: 2rem; }
    @media (max-width: 767px) {
      .dual-form { flex-direction: column; }
    }
    footer {
      background-color: #343a40;
      color: #fff;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function(){
      $(window).scroll(function(){
        if($(this).scrollTop() > 50) {
          $('header').addClass('scrolled');
        } else {
          $('header').removeClass('scrolled');
        }
      });
    });
    function toggleForms() {
      $('#loginCard').fadeToggle();
      $('#signupCard').fadeToggle();
    }
  </script>
</head>
<body>
  <!-- Header with Navigation -->
  <header class="py-3">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="index.php">
          <img src="logo.png" alt="Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto gap-3">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="tours.php">Tours</a></li>
            <li class="nav-item"><a class="nav-link" href="blogs.php">Blogs</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            <li class="nav-item"><a class="nav-link active" href="signup.php">Sign Up</a></li>
          </ul>
        </div>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <div class="container main-content">
    <div class="row dual-form">
      <!-- Sign Up Form (Left Column) -->
      <div class="col-md-6" id="signupCard">
        <div class="card p-4">
          <h2 class="mb-3">Sign Up</h2>
          <form action="process_signup.php" method="post">
            <div class="mb-3">
              <label for="fullname" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullname" name="fullname" placeholder="John Doe" required>
            </div>
            <div class="mb-3">
              <label for="email_signup" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="email_signup" name="email" placeholder="john@example.com" required>
            </div>
            <div class="mb-3">
              <label for="username_signup" class="form-label">Username</label>
              <input type="text" class="form-control" id="username_signup" name="username" placeholder="johndoe" required>
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="password_signup" class="form-label">Password</label>
                <input type="password" class="form-control" id="password_signup" name="password" required>
              </div>
              <div class="col-md-6">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="+251 911 123 456">
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob">
              </div>
              <div class="col-md-6">
                <label for="nationality" class="form-label">Nationality</label>
                <input type="text" class="form-control" id="nationality" name="nationality" placeholder="Ethiopian">
              </div>
            </div>
            <div class="mb-3">
              <label for="preferred_language" class="form-label">Preferred Language</label>
              <input type="text" class="form-control" id="preferred_language" name="preferred_language" placeholder="English">
            </div>
            <div class="mb-3">
              <label for="home_address" class="form-label">Home Address (Optional)</label>
              <textarea class="form-control" id="home_address" name="home_address" rows="2" placeholder="Your address"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Gender</label>
              <div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                  <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                  <label class="form-check-label" for="female">Female</label>
                </div>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
            <p class="mt-3 text-center">Already have an account? <span class="toggle-link" onclick="toggleForms()">Login</span></p>
          </form>
        </div>
      </div>
      <!-- Login Form (Right Column) -->
      <div class="col-md-6" id="loginCard" style="display:none;">
        <div class="card p-4">
          <h2 class="mb-3">Login</h2>
          <form action="process_login.php" method="post">
            <div class="mb-3">
              <label for="username_login" class="form-label">Username or Email</label>
              <input type="text" class="form-control" id="username_login" name="username_email" placeholder="johndoe or john@example.com" required>
            </div>
            <div class="mb-3">
              <label for="password_login" class="form-label">Password</label>
              <input type="password" class="form-control" id="password_login" name="password" required>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
              <label class="form-check-label" for="remember_me">Remember Me</label>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <p class="mt-3 text-center"><a href="forgot_password.php">Forgot Password?</a></p>
            <p class="mt-3 text-center">Don't have an account? <span class="toggle-link" onclick="toggleForms()">Sign Up</span></p>
          </form>
        </div>
      </div>
    </div>
  </div>
  <footer class="mt-4 bg-dark text-white py-3">
    <div class="container text-center">
      <p>&copy; 2025 Asgard Tour Guide. All rights reserved.</p>
      <p>
        <a href="#" class="text-white me-2">Privacy Policy</a>
        <a href="#" class="text-white">Terms & Conditions</a>
      </p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
