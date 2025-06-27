<?php
// booked.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'DB_Bifrost.php';

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if ($booking_id <= 0) {
    echo "Invalid Booking ID.";
    exit();
}

$stmt = $pdo->prepare("SELECT 
    b.booking_id, 
    b.booking_date, 
    b.booking_day, 
    b.status,
    t.title AS tour_title, 
    t.start_date, 
    t.end_date, 
    t.price,
    u.name AS user_name, 
    u.email, 
    u.phone_number
FROM bookings b
JOIN tours t ON b.tour_id = t.tour_id
JOIN users u ON b.user_id = u.user_id
WHERE b.booking_id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$booking) {
    echo "Booking not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation - Asgard Tour Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; }
    .ticket-container { max-width: 800px; margin: 80px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 30px; }
    .ticket-header { text-align: center; margin-bottom: 20px; }
    .ticket-header h1 { font-size: 2rem; }
    .ticket-details { margin-bottom: 20px; }
    .ticket-details p { margin: 5px 0; }
    .ticket-footer { text-align: center; }
    .print-btn { margin-top: 20px; }
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body>
  <div class="container ticket-container">
    <div class="ticket-header">
      <h1>Booking Confirmation</h1>
      <p>Your ticket has been confirmed!</p>
    </div>
    <div class="ticket-details">
      <h4>Ticket Details</h4>
      <p><strong>Ticket ID:</strong> <?php echo $booking['booking_id']; ?></p>
      <p><strong>Tour Name:</strong> <?php echo htmlspecialchars($booking['tour_title']); ?></p>
      <p><strong>Tour Dates:</strong> <?php echo htmlspecialchars($booking['start_date']) . ' to ' . htmlspecialchars($booking['end_date']); ?></p>
      <p><strong>Booking Date:</strong> <?php echo $booking['booking_date']; ?></p>
      <p><strong>Number of Tickets:</strong> <!-- You may add this field if stored --></p>
      <p><strong>Price per Ticket:</strong> ETB <?php echo number_format($booking['price'], 2); ?></p>
      <p><strong>Total Cost:</strong> <!-- Calculate if applicable --></p>
    </div>
    <div class="ticket-details">
      <h4>User Details</h4>
      <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['user_name']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['phone_number']); ?></p>
    </div>
    <div class="ticket-footer no-print">
      <button class="btn btn-primary print-btn" onclick="window.print();"><i class="bi bi-printer-fill"></i> Print Ticket</button>
      <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
