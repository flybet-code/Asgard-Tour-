<?php
/**
 * getTopRevenueDays.php
 *
 * Purpose: Retrieve the top revenue days within the last 30 days.
 * Shows the revenue for each day, including the day of the week and the date.
 */
include 'DB_Bifrost.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT 
            DATE(booking_date) AS date, 
            DAYNAME(booking_date) AS day_of_week,
            COALESCE(SUM(T.price), 0) AS revenue 
        FROM Bookings B
        JOIN Tours T ON B.tour_id = T.tour_id
        WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
        AND B.status = 'Confirmed'
        GROUP BY DATE(booking_date) 
        ORDER BY revenue DESC 
        LIMIT 5
    ");
    
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debugging: Log the data to see if day names and dates are being fetched correctly
    error_log(print_r($data, true)); // Logs the data for debugging purposes
    
    echo json_encode($data); // Return the result as JSON
} catch (Exception $e) {
    error_log("getTopRevenueDays error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to retrieve top revenue days.']);
}
?>
