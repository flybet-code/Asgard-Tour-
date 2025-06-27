<?php
// getTourDetails.php
include 'DB_Bifrost.php';
header('Content-Type: application/json');

$tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;

if ($tour_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM Tours WHERE tour_id = ?");
    $stmt->execute([$tour_id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($tour) {
        echo json_encode($tour);
    } else {
        echo json_encode(['error' => 'Tour not found.']);
    }
} else {
    echo json_encode(['error' => 'Invalid tour ID.']);
}
?>
