<?php
// getPopularTours.php
session_start();
header('Content-Type: application/json');
require 'db_bifrost.php';

$sql = "SELECT t.tour_id, t.title, COUNT(b.booking_id) AS bookings 
        FROM tours t 
        LEFT JOIN bookings b ON t.tour_id = b.tour_id 
        GROUP BY t.tour_id 
        ORDER BY bookings DESC LIMIT 10";
$result = $conn->query($sql);
$tours = [];
while ($row = $result->fetch_assoc()) {
    $tours[] = $row;
}
echo json_encode($tours);
?>
