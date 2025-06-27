<?php
/**
 * getPopularTours.php
 *
 * Purpose: Retrieve the top 3 most popular tours based on confirmed bookings.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT T.tour_id, T.title, COUNT(B.booking_id) AS bookings
        FROM Tours T
        LEFT JOIN Bookings B ON T.tour_id = B.tour_id AND B.status = 'Confirmed'
        GROUP BY T.tour_id
        ORDER BY bookings DESC
        LIMIT 3
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch(Exception $e) {
    error_log("getPopularTours error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to retrieve popular tours.']);
}
?>
