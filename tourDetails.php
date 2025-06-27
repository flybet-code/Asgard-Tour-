<?php
// tourDetails.php
include 'DB_Bifrost.php';
$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;

if($tour_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM Tours WHERE tour_id = ?");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
    if($tour) {
        // Display tour details:
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title><?php echo htmlspecialchars($tour['title']); ?> - Tour Details</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
        </head>
        <body>
          <div class="container mt-4">
            <h1><?php echo htmlspecialchars($tour['title']); ?></h1>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($tour['description'])); ?></p>
            <p><strong>Price:</strong> ETB <?php echo number_format($tour['price'],2); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($tour['location']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($tour['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($tour['end_date']); ?></p>
            <!-- Add more fields as needed -->
            <a href="adminpanel.php" class="btn btn-secondary">Back to Dashboard</a>
          </div>
        </body>
        </html>
        <?php
    } else {
        echo "Tour not found.";
    }
} else {
    echo "Invalid tour ID.";
}
?>
