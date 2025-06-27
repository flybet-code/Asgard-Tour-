<?php
session_start();
include 'DB_bifrost.php'; // Ensure this file name matches your DB connection file

// Handle Login Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use the correct table name (Users) and column name (password_hash)
    $query = "SELECT * FROM Users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verify password against password_hash column
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        
        // Compare role with proper case (as defined in the ENUM: 'traveler', 'admin')
        if (strtolower($user['role']) == 'traveler') {
            echo 'traveler';
        } elseif (strtolower($user['role']) == 'admin') {
            echo 'admin';
        }
    } else {
        echo 'invalid';
    }
    exit();
}

// Handle Registration Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'register') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    // Hash the password using password_hash function
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Only allow travelers to register
    $query = "INSERT INTO Users (name, email, password_hash, role) VALUES (?, ?, ?, 'traveler')";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([$name, $email, $password])) {
        echo 'success';
    } else {
        echo 'error';
    }
    exit();
}

// If the user is already logged in, redirect them
if (isset($_SESSION['user_id'])) {
    if (strtolower($_SESSION['role']) == 'traveler') {
        header('Location: tourlist.php');
        exit();
    } elseif (strtolower($_SESSION['role']) == 'admin') {
        header('Location: adminpanel.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AurisTour - Login/Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
      overflow: hidden;
    }
    /* Full-page background using your image */
    .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('backgroundimages.jpg') no-repeat center center;
      background-size: cover;
      z-index: -1;
    }
    .container-fluid {
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .row {
      width: 100%;
      max-width: 1200px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    /* Left Side Image Box */
    .image-side {
      position: relative;
      background-image: url('image/im.jpg'); /* Image Placeholder */
      background-size: cover;
      background-position: center;
      border-radius: 20px;
      width: 40%;
      height: 70vh;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: none;
    }
    /* Make the image clickable with rounded corners */
    .image-link {
      display: block;
      width: 100%;
      height: 100%;
      border-radius: 20px;
      overflow: hidden;
      text-decoration: none;
    }
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      border-radius: 20px;
    }
    .overlay h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      font-weight: bold;
    }
    .overlay p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
    /* Right Side Form Box */
    .form-side {
      width: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 50px;
    }
    .form-container {
      width: 100%;
      max-width: 400px;
      background-color: white;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      opacity: 0;
      animation: fadeIn 1s ease-in forwards;
    }
    @keyframes fadeIn {
      0% { opacity: 0; }
      100% { opacity: 1; }
    }
    .form-container h2 {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 20px;
    }
    .form-container .mb-3 {
      width: 100%;
    }
    #login-form, #register-form {
      display: block;
    }
    .form-container a {
      text-decoration: none;
      color: #007bff;
      cursor: pointer;
    }
    /* Responsive Design */
    @media (max-width: 768px) {
      .image-side, .form-side {
        width: 100%;
        height: 60vh;
      }
      .overlay h1 {
        font-size: 2rem;
      }
      .overlay p {
        font-size: 1rem;
      }
    }
  </style>
</head>
<body>

<!-- Background Container -->
<div class="background"></div>

<div class="container-fluid">
  <div class="row">
    <!-- Left Side: Clickable Image with Overlay -->
    <div class="col-md-6 image-side">
      <div class="overlay">
          <h1>Welcome to AurisTour</h1>
          <p>Explore the world, one tour at a time</p>
      </div>
    </div>
    <!-- Right Side: Login/Register Form -->
    <div class="col-md-6 form-side">
      <div id="login-form" class="form-container">
        <h2>Login</h2>
        <form id="loginForm">
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="loginEmail" required>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" required>
          </div>
          <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="javascript:void(0);" id="showRegister">Register</a></p>
      </div>

      <div id="register-form" class="form-container" style="display:none;">
        <h2>Register</h2>
        <form id="registerForm">
          <div class="mb-3">
            <label for="registerName" class="form-label">Name</label>
            <input type="text" class="form-control" id="registerName" required>
          </div>
          <div class="mb-3">
            <label for="registerEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="registerEmail" required>
          </div>
          <div class="mb-3">
            <label for="registerPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="registerPassword" required>
          </div>
          <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p class="mt-3">Already have an account? <a href="javascript:void(0);" id="showLogin">Login</a></p>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Toggle between Login and Register forms
    $('#showRegister').click(function() {
      $('#login-form').fadeOut(300, function() {
        $('#register-form').fadeIn(300);
      });
    });
    $('#showLogin').click(function() {
      $('#register-form').fadeOut(300, function() {
        $('#login-form').fadeIn(300);
      });
    });
    // Handle Login form submission via AJAX
    $('#loginForm').submit(function(e) {
      e.preventDefault();
      var email = $('#loginEmail').val();
      var password = $('#loginPassword').val();
      
      $.ajax({
        url: 'loginandregister.php',
        type: 'POST',
        data: { action: 'login', email: email, password: password },
        success: function(response) {
          if (response === 'traveler') {
            window.location.href = 'tourlist.php';
          } else if (response === 'admin') {
            window.location.href = 'adminpanel.php';
          } else {
            alert('Invalid credentials, please try again.');
          }
        }
      });
    });
    // Handle Register form submission via AJAX
    $('#registerForm').submit(function(e) {
      e.preventDefault();
      var name = $('#registerName').val();
      var email = $('#registerEmail').val();
      var password = $('#registerPassword').val();
      
      $.ajax({
        url: 'loginandregister.php',
        type: 'POST',
        data: { action: 'register', name: name, email: email, password: password },
        success: function(response) {
          if (response === 'success') {
            alert('Registration successful! Please log in.');
            $('#register-form').fadeOut(300, function() {
              $('#login-form').fadeIn(300);
            });
          } else {
            alert('Registration failed, please try again.');
          }
        }
      });
    });
  });
</script>

</body>
</html>
