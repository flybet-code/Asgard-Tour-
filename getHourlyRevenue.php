<?php
/**
 * getHourlyRevenue.php
 *
 * Purpose: Retrieve hourly revenue data for the current day.
 * Groups confirmed bookings by the hour of booking_date and sums the tour price.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT HOUR(booking_date) AS hour, 
               COALESCE(SUM(T.price), 0) AS revenue 
        FROM Bookings B
        JOIN Tours T ON B.tour_id = T.tour_id
        WHERE DATE(booking_date) = CURDATE() 
          AND B.status = 'Confirmed'
        GROUP BY HOUR(booking_date)
        ORDER BY HOUR(booking_date) ASC
    ");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (Exception $e) {
    error_log("getHourlyRevenue error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to retrieve hourly revenue.']);
}
?>
