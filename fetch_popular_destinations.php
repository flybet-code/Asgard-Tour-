<?php
// fetch_popular_destinations.php
include 'DB_Bifrost.php';
header('Content-Type: application/json');
try {
    $query = "SELECT T.location, COUNT(*) AS bookings 
              FROM bookings B JOIN tours T ON B.tour_id = T.tour_id 
              WHERE B.status = 'Confirmed'
              GROUP BY T.location
              ORDER BY bookings DESC
              LIMIT 5";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $labels = [];
    $values = [];
    foreach ($data as $row) {
        $labels[] = $row['location'];
        $values[] = $row['bookings'];
    }
    echo json_encode(['labels' => $labels, 'values' => $values]);
} catch (Exception $e) {
    error_log("fetch_popular_destinations error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to fetch popular destinations data']);
}
?>
