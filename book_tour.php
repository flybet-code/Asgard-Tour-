<?php
session_start();
include 'DB_Bifrost.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: tours.php");
    exit;
}

// Check if user is logged in; if not, redirect to signup page
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit;
}

// Validate that a tour_id is provided
if (!isset($_POST['tour_id'])) {
    echo "Invalid booking request.";
    exit;
}

$tour_id = intval($_POST['tour_id']);
$user_id = intval($_SESSION['user_id']);

// Use current date for booking_day (or adjust as needed)
$booking_day = date("Y-m-d");

try {
    // Insert booking into the bookings table with status 'Pending'
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, tour_id, booking_day, status) VALUES (:user_id, :tour_id, :booking_day, :status)");
    $stmt->execute([
       ':user_id'     => $user_id,
       ':tour_id'     => $tour_id,
       ':booking_day' => $booking_day,
       ':status'      => 'Pending'
    ]);
    // Retrieve the newly created booking ID
    $booking_id = $pdo->lastInsertId();
    // Redirect the user to the ticket confirmation page
    header("Location: booked.php?booking_id=" . $booking_id);
    exit;
} catch (PDOException $e) {
    $error_message = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Error - Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Custom CSS -->
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(rgba(0,0,139,0.5), rgba(0,0,139,0.5)), url('image.jpg') fixed center/cover;
      color: #fff;
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1050;
      background-color: transparent;
    }
    .main-content {
      margin-top: 100px;
      padding: 20px;
      background-color: rgba(0,0,0,0.7);
      border-radius: 8px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      text-align: center;
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php"><i class="bi bi-globe2"></i> AsgardTour</a>
      </div>
    </nav>
  </header>
  
  <div class="main-content">
    <h1>Booking Error</h1>
    <p>There was an error processing your booking. Please try again later.</p>
    <p>Error Details: <?php echo htmlspecialchars($error_message); ?></p>
    <a href="tourinfo.php?tour_id=<?php echo $tour_id; ?>" class="btn btn-primary">Back to Tour Info</a>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
