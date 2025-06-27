<?php
header('Content-Type: application/json');

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$dbname     = "asgardtour";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

// Total Revenue: Sum of tour price for all confirmed bookings
$sqlTotal = "SELECT SUM(t.price) AS totalRevenue 
             FROM bookings b 
             JOIN tours t ON b.tour_id = t.tour_id 
             WHERE b.status='Confirmed'";
$result = $conn->query($sqlTotal);
$row = $result->fetch_assoc();
$totalRevenue = $row['totalRevenue'] ? $row['totalRevenue'] : 0;

// Monthly Revenue: For the current month
$sqlMonthly = "SELECT SUM(t.price) AS monthlyRevenue 
               FROM bookings b 
               JOIN tours t ON b.tour_id = t.tour_id 
               WHERE b.status='Confirmed' 
                 AND MONTH(b.booking_date) = MONTH(CURRENT_DATE()) 
                 AND YEAR(b.booking_date) = YEAR(CURRENT_DATE())";
$result = $conn->query($sqlMonthly);
$row = $result->fetch_assoc();
$monthlyRevenue = $row['monthlyRevenue'] ? $row['monthlyRevenue'] : 0;

// Previous Month Revenue: Using a date range covering the previous month
$sqlPrev = "SELECT SUM(t.price) AS prevRevenue 
            FROM bookings b 
            JOIN tours t ON b.tour_id = t.tour_id 
            WHERE b.status='Confirmed'
              AND b.booking_date >= DATE_SUB(CURRENT_DATE(), INTERVAL 2 MONTH)
              AND b.booking_date < DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH)";
$result = $conn->query($sqlPrev);
$row = $result->fetch_assoc();
$prevRevenue = $row['prevRevenue'] ? $row['prevRevenue'] : 0;

// Calculate growth (percentage difference)
$growth = 0;
if ($prevRevenue > 0) {
    $growth = (($monthlyRevenue - $prevRevenue) / $prevRevenue) * 100;
}

echo json_encode([
    "totalRevenue"   => number_format($totalRevenue, 2, '.', ''),
    "monthlyRevenue" => number_format($monthlyRevenue, 2, '.', ''),
    "growth"         => number_format($growth, 2, '.', '')
]);

$conn->close();
?>
