<?php
/**
 * getRevenueData.php
 *
 * Purpose: Retrieve daily revenue data for the past month.
 * Query: Sum total_price from Bookings grouped by booking date.
 * Note: Adjust the date column and table as per your actual schema.
 */

include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT DATE(booking_date) AS date, SUM(total_price) AS revenue
        FROM Bookings
        WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(booking_date)
        ORDER BY DATE(booking_date) ASC
    ");
    $stmt->execute();
    $revenueData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($revenueData);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to retrieve revenue data.']);
}
?>
