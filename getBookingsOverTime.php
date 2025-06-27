<?php
// getBookingsOverTime.php
session_start();
header('Content-Type: application/json');
require 'db_bifrost.php';

$freq = $_GET['freq'] ?? 'daily';
switch ($freq) {
    case 'hourly':
        $sql = "SELECT DATE_FORMAT(booking_date, '%Y-%m-%d %H:00') AS date, COUNT(*) AS bookings 
                FROM bookings 
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m-%d %H:00') 
                ORDER BY booking_date ASC";
        break;
    case 'monthly':
        $sql = "SELECT DATE_FORMAT(booking_date, '%Y-%m') AS date, COUNT(*) AS bookings 
                FROM bookings 
                GROUP BY DATE_FORMAT(booking_date, '%Y-%m') 
                ORDER BY booking_date ASC";
        break;
    default: // Daily grouping
        $sql = "SELECT DATE(booking_date) AS date, COUNT(*) AS bookings 
                FROM bookings 
                GROUP BY DATE(booking_date) 
                ORDER BY booking_date ASC";
}
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
