<?php
// managetour.php
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
  <title>Manage Tours - Asgard Tour Guide</title>
  <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h2>Manage Tours</h2>
    <div id="toursContainer">
      <!-- Tours will be loaded here via AJAX -->
    </div>
  </div>

  <script src="script.js"></script>
  <script>
    $(document).ready(function() {
      loadTours();
    });
  </script>
</body>
</html>