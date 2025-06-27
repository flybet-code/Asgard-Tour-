<?php
// notifications.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: loginandregister.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications & Alerts - Asgard Tour Guide</title>
  <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h2>Notifications & Alerts</h2>
    <div id="notificationsList">
      <!-- AJAX-loaded notifications will appear here -->
    </div>
  </div>

  <script src="script.js"></script>
  <script>
    $(document).ready(function() {
      loadNotifications(); // Example function to load notifications
    });
  </script>
</body>
</html>