<?php
/**
 * getTotalRevenue.php
 *
 * Purpose: Calculate total revenue from confirmed bookings.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(IFNULL(Tours.price, 0)), 0) AS totalRevenue
        FROM Bookings
        JOIN Tours ON Bookings.tour_id = Tours.tour_id
        WHERE Bookings.status = 'Confirmed'
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || $result['totalRevenue'] == 0) {
        echo json_encode(['message' => 'No available revenue']);
    } else {
        echo json_encode($result);
    }
} catch (Exception $e) {
    error_log("getTotalRevenue error: " . $e->getMessage());
    echo json_encode([
        'error' => 'Failed to retrieve total revenue.',
        'details' => $e->getMessage()
    ]);
}
?>
