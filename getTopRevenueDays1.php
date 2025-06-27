<?php
// getTopRevenueDays.php
session_start();
header('Content-Type: application/json');
require 'db_bifrost.php';

$sql = "SELECT DATE(b.booking_date) AS date, SUM(t.price) AS revenue, DAYNAME(b.booking_date) AS day_of_week
        FROM bookings b 
        JOIN tours t ON b.tour_id = t.tour_id 
        GROUP BY DATE(b.booking_date)
        ORDER BY revenue DESC LIMIT 10";
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
