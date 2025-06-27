<?php
// getRevenueOverTime.php
session_start();
header('Content-Type: application/json');
require 'db_DB_Bifrost.php';

$freq = $_GET['freq'] ?? 'daily';
switch ($freq) {
    case 'hourly':
        $sql = "SELECT DATE_FORMAT(b.booking_date, '%Y-%m-%d %H:00') AS label, SUM(t.price) AS revenue 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m-%d %H:00') 
                ORDER BY b.booking_date ASC";
        break;
    case 'monthly':
        $sql = "SELECT DATE_FORMAT(b.booking_date, '%Y-%m') AS label, SUM(t.price) AS revenue 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                GROUP BY DATE_FORMAT(b.booking_date, '%Y-%m') 
                ORDER BY b.booking_date ASC";
        break;
    default:  // Daily grouping
        $sql = "SELECT DATE(b.booking_date) AS label, SUM(t.price) AS revenue 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                GROUP BY DATE(b.booking_date) 
                ORDER BY b.booking_date ASC";
}
$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['revenue'] = $row['revenue'] ? $row['revenue'] : 0;
    $data[] = $row;
}
echo json_encode($data);
?>
