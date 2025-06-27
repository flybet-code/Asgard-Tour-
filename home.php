<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AsgardTour - Login & Register</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Full screen background image */
    body, html {
      height: 100%;
      margin: 0;
      background: url('image.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: Arial, sans-serif;
    }
    /* Main container split into two sections */
    .main-container {
      display: flex;
      height: 100%;
    }
    /* Left Box: 70% width with gradient overlay and centered content */
    .left-box {
      flex: 0 0 70%;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .left-box::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(135deg, rgba(75, 0, 130, 0.7), rgba(138, 43, 226, 0.7));
      z-index: 1;
    }
    .left-content {
      position: relative;
      z-index: 2;
      text-align: center;
      color: #fff;
      animation: fadeInDown 1s;
    }
    .left-content h1 {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .left-content img {
      max-width: 80%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
    }
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    /* Right Box: Contains the forms with a floating, rounded design */
    .right-box {
      flex: 1;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }
    .form-container {
      width: 100%;
      max-width: 400px;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
      background: #fff;
      overflow: hidden;
    }
    /* Toggle button styling */
    .toggle-btns {
      display: flex;
      justify-content: space-around;
      margin-bottom: 30px;
    }
    .toggle-btns button {
      background: none;
      border: none;
      font-size: 1.1rem;
      padding: 10px;
      cursor: pointer;
      transition: color 0.3s;
    }
    .toggle-btns button.active {
      color: #5cb85c;
      border-bottom: 2px solid #5cb85c;
    }
    /* Form field styling with animated labels and vibrant borders */
    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }
    .form-group input {
      width: 100%;
      padding: 10px 10px 10px 40px;
      border: 2px solid #ccc;
      border-radius: 5px;
      transition: border-color 0.3s;
    }
    .form-group input:focus {
      border-color: #5cb85c;
      outline: none;
    }
    .form-group label {
      position: absolute;
      top: 50%;
      left: 40px;
      transform: translateY(-50%);
      color: #999;
      transition: 0.3s;
      pointer-events: none;
    }
    .form-group input:focus + label,
    .form-group input:not(:placeholder-shown) + label {
      top: 0;
      left: 15px;
      font-size: 0.8rem;
      color: #5cb85c;
      background: #fff;
      padding: 0 5px;
    }
    .input-icon {
      position: absolute;
      top: 50%;
      left: 10px;
      transform: translateY(-50%);
      color: #5cb85c;
      transition: transform 0.3s;
    }
    .form-group input:focus ~ .input-icon {
      transform: translateY(-50%) scale(1.2);
    }
    /* Vibrant animated submit button */
    .btn-custom {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      background: linear-gradient(45deg, #5cb85c, #66bb6a);
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      transition: transform 0.3s, background 0.3s;
    }
    .btn-custom:hover {
      transform: scale(1.05);
    }
    /* Transition effect for form toggling */
    .form-wrapper {
      transition: transform 0.5s ease-in-out;
    }
    /* Alert messages */
    .alert {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
    }
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>
<body>
  <div class="main-container">
    <!-- Left Box: Background image with gradient overlay, animated welcome text, and travel picture -->
    <div class="left-box">
      <div class="left-content">
        <h1>Welcome to AsgardTour!</h1>
        <img src="picture.jpg" alt="Travel Inspiration">
      </div>
    </div>
    <!-- Right Box: Registration and Login Forms -->
    <div class="right-box">
      <div class="form-container">
        <!-- Display error or success messages -->
        <?php if ($error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <!-- Toggle Buttons for switching between forms -->
        <div class="toggle-btns">
          <button id="loginToggle" class="active">Login</button>
          <button id="registerToggle">Register</button>
        </div>
        <!-- Login Form -->
        <div id="loginForm" class="form-wrapper">
          <form action="process_login.php" method="POST">
            <div class="form-group">
              <input type="email" name="email" id="loginEmail" placeholder=" " required>
              <label for="loginEmail">Email</label>
              <i class="fas fa-envelope input-icon"></i>
            </div>
            <div class="form-group">
              <input type="password" name="password" id="loginPassword" placeholder=" " required>
              <label for="loginPassword">Password</label>
              <i class="fas fa-lock input-icon"></i>
            </div>
            <button type="submit" class="btn-custom">Take Me to My Journey!</button>
          </form>
        </div>
        <!-- Register Form (for travelers only) -->
        <div id="registerForm" class="form-wrapper" style="display: none;">
          <form action="process_register.php" method="POST">
            <div class="form-group">
              <input type="text" name="name" id="regName" placeholder=" " required>
              <label for="regName">Name</label>
              <i class="fas fa-user input-icon"></i>
            </div>
            <div class="form-group">
              <input type="email" name="email" id="regEmail" placeholder=" " required>
              <label for="regEmail">Email</label>
              <i class="fas fa-envelope input-icon"></i>
            </div>
            <div class="form-group">
              <input type="password" name="password" id="regPassword" placeholder=" " required>
              <label for="regPassword">Password</label>
              <i class="fas fa-lock input-icon"></i>
            </div>
            <button type="submit" class="btn-custom">Join the Adventure!</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery and Bootstrap JS for form toggle functionality -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Toggle between login and register forms with smooth animations
    $(document).ready(function() {
      $('#loginToggle').click(function() {
        $(this).addClass('active');
        $('#registerToggle').removeClass('active');
        $('#loginForm').show();
        $('#registerForm').hide();
      });
      $('#registerToggle').click(function() {
        $(this).addClass('active');
        $('#loginToggle').removeClass('active');
        $('#loginForm').hide();
        $('#registerForm').show();
      });
    });
  </script>
</body>
</html>
